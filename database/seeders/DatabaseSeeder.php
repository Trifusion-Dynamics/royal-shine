<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            'is_admin' => true,
        ]);

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
            Category::create([
                'name' => $name,
                'slug' => str($name)->slug(),
                'description' => null,
                'image' => null,
                'is_featured' => false,
                'status' => 'active',
                'seo_title' => null,
                'seo_description' => null,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
