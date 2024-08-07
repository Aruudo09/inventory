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
        Schema::create('pemakaian_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('use_id')->references('id')->on('pemakaians')->onDelete('cascade');
            $table->foreignId('item_id');
            $table->double('qtyUse');
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
        //
    }
};
