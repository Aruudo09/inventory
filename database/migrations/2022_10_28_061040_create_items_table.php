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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('partNumber')->unique();
            $table->string('itemName');
            $table->double('stock', 7, 2);
            $table->double('firstStock', 7, 2);
            $table->double('stockIn', 7, 2);
            $table->double('stockOut', 7, 2);
            $table->char('satuan', 5);
            $table->integer('harga');
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
        Schema::dropIfExists('items');
    }
};
