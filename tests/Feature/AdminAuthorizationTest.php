<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_manager_is_limited_to_catalogue_and_content_modules(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
            'role' => 'content_manager',
        ]);

        $this->actingAs($user)->get('/admin')->assertOk();
        $this->actingAs($user)->get('/admin/products')->assertOk();
        $this->actingAs($user)->get('/admin/enquiries')->assertForbidden();
        $this->actingAs($user)->get('/admin/users')->assertForbidden();
    }

    public function test_enquiry_manager_can_review_enquiries_but_not_catalogue(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
            'role' => 'enquiry_manager',
        ]);

        $this->actingAs($user)->get('/admin/enquiries')->assertOk();
        $this->actingAs($user)->get('/admin/customers')->assertOk();
        $this->actingAs($user)->get('/admin/products')->assertForbidden();
        $this->actingAs($user)->get('/admin/settings')->assertForbidden();
    }

    public function test_non_admin_accounts_cannot_access_filament(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'role' => 'customer',
        ]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }
}
