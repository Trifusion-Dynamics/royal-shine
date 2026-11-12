<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminName = config('royal_shine.admin.name');
        $adminEmail = config('royal_shine.admin.email');
        $adminPassword = config('royal_shine.admin.password');

        if (filled($adminEmail) && filled($adminPassword)) {
            User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName ?: 'Royal Shine Admin',
                    'password' => $adminPassword,
                    'is_admin' => true,
                    'role' => 'super_admin',
                ],
            );
        } else {
            $this->command?->warn('Admin account was not seeded. Set ADMIN_EMAIL and ADMIN_PASSWORD first.');
        }

        $categories = [
            'Necklaces',
            'Earrings',
            'Rings',
            'Bangles & Bracelets',
            'Bridal Jewellery',
            'Mangalsutra',
            'Nose Pins',
            'Anklets & Payal',
            'Hair Accessories',
            'Silver Jewellery',
            'Gold Jewellery',
            'Artificial Jewellery',
        ];

        foreach ($categories as $index => $name) {
            Category::firstOrCreate(
                ['slug' => str($name)->slug()],
                [
                    'name' => $name,
                    'description' => null,
                    'image' => null,
                    'is_featured' => false,
                    'status' => 'active',
                    'seo_title' => null,
                    'seo_description' => null,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
