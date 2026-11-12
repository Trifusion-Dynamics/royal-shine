<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('product_reference')->nullable();
            $table->text('message');
            $table->string('status')->default('new');
            $table->text('internal_notes')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index('status');
            $table->index('mobile');
            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
