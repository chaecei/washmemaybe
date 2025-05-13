<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');  // Foreign key column
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamp('picked_up_at')->nullable()->after('grand_total');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('picked_up_at');
        });
    }

};
