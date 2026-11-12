<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('collection_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('metal_type')->nullable();
            $table->string('gold_purity')->nullable();
            $table->decimal('weight', 10, 3)->nullable();
            $table->string('weight_unit')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('price_on_request')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('collection_id');
            $table->index('sku');
            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
