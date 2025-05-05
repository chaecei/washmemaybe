<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Foreign key reference to customers
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('service_type', ['Wash and Fold', 'Wash and Iron', 'Dry Clean']);
            $table->float('total_load');
            $table->enum('detergent', ['Regular Detergent', 'Hypoallergenic', 'Customer Provided']);
            $table->enum('softener', ['No Softener', 'Regular', 'Floral', 'Baby Powder', 'Unscented']);
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null'); // Link to categories table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    } 
};
