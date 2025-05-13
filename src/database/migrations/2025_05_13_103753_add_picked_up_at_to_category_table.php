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
        Schema::table('category', function (Blueprint $table) {
            $table->timestamp('picked_up_at')->nullable();  // Add 'picked_up_at' column
        });
    }

    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn(['picked_up_at']);
        });
    }

};
