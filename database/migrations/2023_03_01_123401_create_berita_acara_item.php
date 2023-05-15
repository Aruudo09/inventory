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
        Schema::create('berita_acara_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')->referance('id')->on('berita_acara')->onDelete('cascade');
            $table->foreignId('item_id');
            $table->double('qtyBa');
            $table->char('satuan', 5);
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
        Schema::dropIfExists('berita_acara_item');
    }
};
