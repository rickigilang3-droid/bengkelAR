<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('part_code')->unique();
            $table->string('name');
            $table->string('category');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(3);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->string('unit')->default('Pcs');
            $table->string('supplier')->nullable();
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};

