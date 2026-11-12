<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('stone_details')->nullable()->after('weight_unit');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stone_details');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
