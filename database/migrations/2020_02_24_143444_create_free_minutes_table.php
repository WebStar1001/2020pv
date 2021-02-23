<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeMinutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_minutes', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('advisor_id')->unsigned();
	        $table->integer('customer_id')->unsigned();
	        $table->integer('free_seconds');
            $table->timestamps();

	        $table->foreign('advisor_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_minutes');
    }
}
