<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts_histories', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('customer_id')->unsigned();
	        $table->integer('advisor_id')->unsigned();
	        $table->integer('chat_session_id')->unsigned();
            $table->timestamps();

	        $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('advisor_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('chat_session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts_histories');
    }
}
