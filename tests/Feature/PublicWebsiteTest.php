<?php

namespace Tests\Feature;

use App\Enums\GoldPurity;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_and_seo_endpoints_render(): void
    {
        foreach ([
            '/',
            '/about-us',
            '/jewellery',
            '/gallery',
            '/contact-us',
            '/privacy-policy',
            '/terms-and-conditions',
            '/search',
            '/collections',
            '/testimonials',
            '/sitemap.xml',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }

        $this->get('/missing-page')->assertNotFound();
    }

    public function test_contact_form_stores_a_contact_enquiry(): void
    {
        $response = $this->post('/contact-us', [
            'name' => 'A Customer',
            'mobile' => '9876543210',
            'email' => 'customer@example.com',
            'subject' => 'Collection question',
            'message' => 'Please share more information about your collection.',
        ]);

        $response->assertRedirect(route('enquiry.success'));
        $this->assertDatabaseHas('enquiries', [
            'name' => 'A Customer',
            'email' => 'customer@example.com',
            'subject' => 'Collection question',
            'enquiry_type' => 'contact',
            'status' => 'new',
        ]);
    }

    public function test_product_enquiry_rejects_unpublished_products(): void
    {
        $category = Category::create([
            'name' => 'Necklaces',
            'slug' => 'necklaces',
            'status' => 'active',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Draft necklace',
            'slug' => 'draft-necklace',
            'sku' => 'RS-DRAFT-1',
            'status' => 'draft',
        ]);

        $this->post('/enquiry', [
            'name' => 'A Customer',
            'mobile' => '9876543210',
            'email' => 'customer@example.com',
            'product_id' => $product->id,
            'message' => 'I would like to know more.',
        ])->assertSessionHasErrors('product_id');

        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_catalogue_reflects_products_added_after_the_page_is_loaded(): void
    {
        Cache::forget('jewellery.page');
        $this->get('/jewellery')->assertOk();

        $category = Category::create([
            'name' => 'Necklaces',
            'slug' => 'necklaces',
            'status' => 'active',
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Ruby necklace',
            'slug' => 'ruby-necklace',
            'sku' => 'RS-RUBY-1',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $this->get('/jewellery')->assertOk()->assertSee('Ruby necklace');
    }

    public function test_product_images_keep_one_primary_image_and_promote_a_replacement(): void
    {
        $category = Category::create([
            'name' => 'Necklaces',
            'slug' => 'necklaces',
            'status' => 'active',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Ruby necklace',
            'slug' => 'ruby-necklace',
            'sku' => 'RS-RUBY-1',
            'status' => 'published',
        ]);

        $first = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'products/first.jpg',
            'sort_order' => 1,
        ]);
        $second = ProductImage::create([
            'product_id' => $product->id,
            'image' => 'products/second.jpg',
            'sort_order' => 2,
            'is_primary' => true,
        ]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);

        $second->delete();

        $this->assertTrue($first->fresh()->is_primary);
    }

    public function test_missing_product_images_use_the_branded_placeholder(): void
    {
        $images = app(\App\Services\ImageService::class);

        $this->assertSame(
            asset('images/placeholder.svg'),
            $images->url('products/missing-test-image.jpg'),
        );
        $this->assertSame(
            'https://example.com/image.jpg',
            $images->url('https://example.com/image.jpg'),
        );
    }

    public function test_category_and_product_pages_render_gold_purity_values(): void
    {
        $category = Category::create([
            'name' => 'Necklaces',
            'slug' => 'necklaces',
            'status' => 'active',
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Gold necklace',
            'slug' => 'gold-necklace',
            'sku' => 'RS-GOLD-1',
            'gold_purity' => GoldPurity::K18,
            'status' => 'published',
        ]);

        $this->get('/jewellery/necklaces')->assertOk()->assertSee('18K Gold');
        $this->get('/product/gold-necklace')->assertOk()->assertSee('18K Gold');
    }
}
