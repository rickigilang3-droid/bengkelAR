<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('service_type'); // Ganti Oli, Perbaikan, Maintenance, dll
            $table->text('description');
            $table->dateTime('entered_at');
            $table->dateTime('completed_at');
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'transfer', 'card'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('vehicle_id');
            $table->index('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_histories');
    }
};
