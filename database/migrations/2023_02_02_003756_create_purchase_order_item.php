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
        Schema::create('purchase_order_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreignId('item_id');
            $table->double('qtyPo');
            $table->double('qtyBa')->default(0);
            $table->char('satuan', 5);
            $table->integer('harga');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_item');
    }
};
