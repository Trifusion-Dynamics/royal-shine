<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('heading');
            $table->text('subheading')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('status')->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
