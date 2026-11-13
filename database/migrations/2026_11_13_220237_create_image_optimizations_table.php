<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_optimizations', function (Blueprint $table) {
            $table->id();
            $table->string('original_path');
            $table->string('webp_path')->nullable();
            $table->string('avif_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('medium_path')->nullable();
            $table->string('large_path')->nullable();
            $table->unsignedInteger('original_size')->nullable();
            $table->unsignedInteger('optimized_size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('processing_status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->unique('original_path');
            $table->index('processing_status');
            $table->index('mime_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_optimizations');
    }
};
