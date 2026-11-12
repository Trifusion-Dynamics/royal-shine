<?php

namespace Tests\Feature;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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

    public function test_last_super_admin_cannot_be_demoted_or_deleted(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
            'role' => 'super_admin',
        ]);
        $this->actingAs($user);

        $this->assertFalse(UserResource::canDelete($user));

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm(['role' => 'admin'])
            ->call('save')
            ->assertHasFormErrors(['role']);

        $this->assertSame('super_admin', $user->fresh()->role);
    }
}
