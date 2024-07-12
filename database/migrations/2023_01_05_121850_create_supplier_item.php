<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreignId('item_id')->referance('id')->on('item');
            $table->timestamps();
            $table->integer('harga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_item');
    }
};
