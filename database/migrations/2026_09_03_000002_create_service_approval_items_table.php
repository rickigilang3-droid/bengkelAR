<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_approval_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_queue_id')->constrained('service_queues')->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('estimated_cost', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index('service_queue_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_approval_items');
    }
};

