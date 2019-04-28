<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequesterStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requester_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('requester_id');
            $table->enum('name', ['pending', 'signed', 'canceled']);
            $table->unique(['requester_id', 'name']);
            $table->foreign('requester_id')->references('id')->on('requesters')->onDelete('cascade');
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
        Schema::dropIfExists('requester_status');
    }
}
