<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Banner;
use App\Enums\EnquiryStatus;
use Illuminate\Support\Str;

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
            [
                'name' => 'Necklaces',
                'image' => 'images/royal-shine-gold-diamond-necklace-chandelier-earring-set-original.png',
                'is_featured' => true,
            ],
            [
                'name' => 'Earrings',
                'image' => 'images/royal-shine-earrings-collection-hero.webp',
                'is_featured' => true,
            ],
            [
                'name' => 'Rings',
                'image' => 'images/royal-shine-gold-solitaire-silver-floral-stud-earrings-original.png',
                'is_featured' => true,
            ],
            [
                'name' => 'Bangles & Bracelets',
                'image' => 'images/royal-shine-gold-silver-hoop-earrings-set-original.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Bridal Jewellery',
                'image' => 'images/royal-shine-antique-gold-rani-haar-ruby-emerald-original.png',
                'is_featured' => true,
            ],
            [
                'name' => 'Mangalsutra',
                'image' => 'images/royal-shine-gold-teardrop-diamond-pendant-necklace-original.png.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Nose Pins',
                'image' => 'images/royal-shine-kids-star-heart-butterfly-stud-earrings-original.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Anklets & Payal',
                'image' => 'images/royal-shine-white-gold-diamond-stud-earrings-original.png.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Hair Accessories',
                'image' => 'images/royal-shine-bahubali-temple-gold-silver-jhumka-original.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Silver Jewellery',
                'image' => 'images/royal-shine-gold-silver-chandbali-lotus-pearl-earrings-original.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Gold Jewellery',
                'image' => 'images/royal-shine-gold-kundan-silver-filigree-maang-tikka-collection-original.png',
                'is_featured' => false,
            ],
            [
                'name' => 'Artificial Jewellery',
                'image' => 'images/royal-shine-gold-silver-ear-cuff-collection-original.png',
                'is_featured' => false,
            ],
        ];

        foreach ($categories as $index => $category) {
            Category::firstOrCreate(
                ['slug' => str($category['name'])->slug()],
                [
                    'name' => $category['name'],
                    'image' => $category['image'],
                    'is_featured' => $category['is_featured'],
                    'status' => 'active',
                    'seo_title' => null,
                    'seo_description' => null,
                    'sort_order' => $index + 1,
                ],
            );
        }

        // Seed gallery images from public/images folder
        $galleryImages = [
            ['title' => 'Gold Jhumka Collection', 'description' => 'Traditional gold jhumka earrings', 'image' => 'images/royal-shine-bahubali-temple-gold-silver-jhumka-original.png', 'sort_order' => 1],
            ['title' => 'Earrings Collection', 'description' => 'Elegant earrings for every occasion', 'image' => 'images/royal-shine-earrings-collection-hero.webp', 'sort_order' => 2],
            ['title' => 'Kundan Maang Tikka', 'description' => 'Exquisite kundangold maang tikka', 'image' => 'images/royal-shine-gold-kundan-silver-filigree-maang-tikka-collection-original.png', 'sort_order' => 3],
            ['title' => 'Chandbali Earrings', 'description' => 'Lotus pearl chandbali earrings', 'image' => 'images/royal-shine-gold-silver-chandbali-lotus-pearl-earrings-original.png', 'sort_order' => 4],
            ['title' => 'Ear Cuff Collection', 'description' => 'Modern ear cuff designs', 'image' => 'images/royal-shine-gold-silver-ear-cuff-collection-original.png', 'sort_order' => 5],
            ['title' => 'Hoop Earrings Set', 'description' => 'Classic hoop earrings', 'image' => 'images/royal-shine-gold-silver-hoop-earrings-set-original.png', 'sort_order' => 6],
            ['title' => 'Threader Earrings', 'description' => 'Sui dhaga threader earrings', 'image' => 'images/royal-shine-gold-silver-sui-dhaga-threader-earrings-original.png', 'sort_order' => 7],
            ['title' => 'Chandelier Earrings', 'description' => 'Tiered chandelier earrings', 'image' => 'images/royal-shine-gold-silver-tiered-chandelier-earrings-original.png', 'sort_order' => 8],
            ['title' => 'Solitaire Stud Earrings', 'description' => 'Gold solitaire stud earrings', 'image' => 'images/royal-shine-gold-solitaire-silver-floral-stud-earrings-original.png', 'sort_order' => 9],
            ['title' => 'Teardrop Dangler', 'description' => 'Teardrop linear dangler earrings', 'image' => 'images/royal-shine-gold-teardrop-silver-linear-dangler-earrings-original.png', 'sort_order' => 10],
            ['title' => 'Kids Collection', 'description' => 'Star heart butterfly stud earrings', 'image' => 'images/royal-shine-kids-star-heart-butterfly-stud-earrings-original.png', 'sort_order' => 11],
            ['title' => 'Diamond Stud Earrings', 'description' => 'White gold diamond stud earrings', 'image' => 'images/royal-shine-white-gold-diamond-stud-earrings-original.png.png', 'sort_order' => 12],
        ];

        foreach ($galleryImages as $galleryItem) {
            Gallery::firstOrCreate(
                ['title' => $galleryItem['title']],
                [
                    'description' => $galleryItem['description'],
                    'image' => $galleryItem['image'],
                    'alt_text' => $galleryItem['title'],
                    'status' => 'active',
                    'sort_order' => $galleryItem['sort_order'],
                ],
            );
        }

        // Seed settings
        $settings = [
            'site_name' => 'Royal Shine',
            'logo' => 'images/royal-shine-logo.png',
            'favicon' => 'images/royal-shine-logo.png',
            'phone' => '+91 98765 43210',
            'whatsapp_number' => '+91 98765 43210',
            'email' => 'hello@royalshine.com',
            'address' => 'Shop No. 101, Jewellery Market, Connaught Place, New Delhi - 110001',
            'map_link' => 'https://maps.google.com/?q=Royal+Shine+Jewellery+New+Delhi',
            'footer_text' => 'Royal Shine - Premium Jewellery Crafted with Care. All rights reserved.',
            'social_facebook' => 'https://facebook.com/royalshine',
            'social_instagram' => 'https://instagram.com/royalshine',
            'social_whatsapp' => 'https://wa.me/919876543210',
            'social_twitter' => 'https://twitter.com/royalshine',
            'announcement_enabled' => false,
            'announcement_text' => '',
            'announcement_link' => '',
            'email_notifications_enabled' => false,
            'business_hours' => '10:00 AM - 8:00 PM (Mon-Sat)',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general'],
            );
        }

        // Seed sample products
        $necklaceCategory = Category::where('slug', 'necklaces')->first();
        $earringsCategory = Category::where('slug', 'earrings')->first();
        $ringsCategory = Category::where('slug', 'rings')->first();
        $banglesCategory = Category::where('slug', 'bangles-bracelets')->first();
        $mangalsutraCategory = Category::where('slug', 'mangalsutra')->first();
        $nosePinsCategory = Category::where('slug', 'nose-pins')->first();

        $sampleProducts = [
            [
                'name' => '18K Gold Necklace with Pendant',
                'slug' => '18k-gold-necklace-pendant',
                'sku' => 'RN-001',
                'category_id' => $necklaceCategory?->id,
                'metal_type' => 'gold',
                'gold_purity' => '18K',
                'weight' => 12.5,
                'weight_unit' => 'g',
                'price' => 45000.00,
                'price_on_request' => false,
                'status' => 'published',
                'is_featured' => true,
                'short_description' => 'Elegant 18K gold necklace with pendant',
                'description' => 'A beautiful 18K gold necklace with exquisite pendant design, perfect for special occasions.',
                'stone_details' => 'Embedded with small diamonds',
                'seo_title' => '18K Gold Necklace Pendant - Royal Shine',
                'seo_description' => 'Buy 18K gold necklace pendant online at Royal Shine. Premium quality jewellery.',
            ],
            [
                'name' => 'Diamond Stud Earrings',
                'slug' => 'diamond-stud-earrings',
                'sku' => 'RE-001',
                'category_id' => $earringsCategory?->id,
                'metal_type' => 'white_gold',
                'gold_purity' => '14K',
                'weight' => 3.2,
                'weight_unit' => 'g',
                'price' => 28000.00,
                'price_on_request' => false,
                'status' => 'published',
                'is_featured' => true,
                'short_description' => 'Classic diamond stud earrings in 14K white gold',
                'description' => 'Timeless diamond stud earrings crafted in 14K white gold with GIA certified diamonds.',
                'stone_details' => '0.50 carat GIA certified diamonds',
                'seo_title' => 'Diamond Stud Earrings 14K White Gold - Royal Shine',
                'seo_description' => 'Shop diamond stud earrings in 14K white gold at Royal Shine.',
            ],
            [
                'name' => 'Gold Ring with Ruby',
                'slug' => 'gold-ring-ruby',
                'sku' => 'RR-001',
                'category_id' => $ringsCategory?->id,
                'metal_type' => 'gold',
                'gold_purity' => '22K',
                'weight' => 8.0,
                'weight_unit' => 'g',
                'price_on_request' => true,
                'status' => 'published',
                'is_featured' => false,
                'short_description' => '22K gold ring with natural ruby stone',
                'description' => 'Exquisite 22K gold ring featuring a natural ruby stone, perfect for engagements.',
                'stone_details' => 'Natural ruby stone, 1.5 carat',
                'seo_title' => '22K Gold Ruby Ring - Royal Shine',
                'seo_description' => '22K gold ring with natural ruby at Royal Shine Jewellery.',
            ],
            [
                'name' => 'Gold Bangles Set',
                'slug' => 'gold-bangles-set',
                'sku' => 'RB-001',
                'category_id' => $banglesCategory?->id,
                'metal_type' => 'gold',
                'gold_purity' => '22K',
                'weight' => 25.0,
                'weight_unit' => 'g',
                'price' => 85000.00,
                'price_on_request' => false,
                'status' => 'published',
                'is_featured' => true,
                'short_description' => 'Traditional 22K gold bangles set',
                'description' => 'Classic 22K gold bangles with intricate design, perfect for weddings and festivals.',
                'stone_details' => 'No stones',
                'seo_title' => '22K Gold Bangles Set - Royal Shine',
                'seo_description' => 'Buy 22K gold bangles set online at Royal Shine.',
            ],
            [
                'name' => 'Mangalsutra with Black Beads',
                'slug' => 'mangalsutra-black-beads',
                'sku' => 'RM-001',
                'category_id' => $mangalsutraCategory?->id,
                'metal_type' => 'gold',
                'gold_purity' => '18K',
                'weight' => 10.0,
                'weight_unit' => 'g',
                'price' => 55000.00,
                'price_on_request' => false,
                'status' => 'published',
                'is_featured' => true,
                'short_description' => 'Traditional 18K gold mangalsutra with black beads',
                'description' => 'Elegant 18K gold mangalsutra with black beads and gold pendant, designed for married women.',
                'stone_details' => 'Black beads and gold pendant',
                'seo_title' => '18K Gold Mangalsutra - Royal Shine',
                'seo_description' => 'Shop 18K gold mangalsutra online at Royal Shine.',
            ],
            [
                'name' => 'Gold Nose Pin',
                'slug' => 'gold-nose-pin',
                'sku' => 'RN-002',
                'category_id' => $nosePinsCategory?->id,
                'metal_type' => 'gold',
                'gold_purity' => '22K',
                'weight' => 2.5,
                'weight_unit' => 'g',
                'price' => 12000.00,
                'price_on_request' => false,
                'status' => 'published',
                'is_featured' => false,
                'short_description' => 'Delicate 22K gold nose pin',
                'description' => 'Beautiful 22K gold nose pin with small stone, perfect for daily wear.',
                'stone_details' => 'Small diamond stone',
                'seo_title' => '22K Gold Nose Pin - Royal Shine',
                'seo_description' => 'Buy 22K gold nose pin online at Royal Shine.',
            ],
        ];

        foreach ($sampleProducts as $productData) {
            $product = Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData,
            );

            // Add product images for each product
            if (!$product->images()->exists()) {
                $productImages = [
                    'images/royal-shine-gold-diamond-necklace-chandelier-earring-set-original.png',
                    'images/royal-shine-gold-teardrop-diamond-pendant-necklace-original.png.png',
                    'images/royal-shine-gold-kundan-silver-filigree-maang-tikka-collection-original.png',
                ];

                foreach ($productImages as $index => $imagePath) {
                    $product->images()->create([
                        'image' => $imagePath,
                        'alt_text' => $productData['name'] . ' Image ' . ($index + 1),
                        'sort_order' => $index + 1,
                        'is_primary' => $index === 0,
                    ]);
                }
            }
        }
    }
}
