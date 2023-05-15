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
        Schema::create('purchase_request_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->references('id')->on('purchase_requests')->onDelete('cascade');
            $table->foreignId('item_id');
            $table->double('qtyPr');
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
        Schema::dropIfExists('table_pr');
    }
};
