<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('session_id')->unsigned();
	        $table->integer('customer_id')->unsigned();
	        $table->integer('advisor_id')->unsigned();
	        $table->tinyInteger('rating');
	        $table->text('text')->nullable();
            $table->timestamps();

	        $table->foreign('session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
	        $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('advisor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
