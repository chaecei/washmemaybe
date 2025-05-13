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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Renamed to 'order_id' for clarity
            $table->foreign('service_number')->references('id')->on('orders')->onDelete('cascade'); // Foreign key reference to 'orders'
            $table->string('status'); // Status field
            $table->integer('days_unclaimed')->default(0); // Days unclaimed column (default 0)
            $table->string('name');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
