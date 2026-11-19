<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_history_id')->constrained('service_histories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->default(5);
            $table->integer('mechanic_rating')->default(5);
            $table->text('comment')->nullable();
            $table->text('admin_reply')->nullable();
            $table->timestamps();

            $table->index('service_history_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
    }
};

