<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('royal_shine.admin.email');
        $password = config('royal_shine.admin.password');

        if (! filled($email) || ! filled($password)) {
            $this->command?->warn('Admin account not created. Set ADMIN_EMAIL and ADMIN_PASSWORD in Render.');
            return;
        }

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => config('royal_shine.admin.name') ?: 'Royal Shine Admin',
                'password' => $password,
                'is_admin' => true,
                'role' => 'super_admin',
            ],
        );
    }
}
