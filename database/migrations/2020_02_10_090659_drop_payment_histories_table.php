<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::dropIfExists('payment_histories');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::create('payment_histories', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('user_id')->unsigned();
		    $table->decimal('balance_before', 15, 10);
		    $table->decimal('balance_after', 15, 10);
		    $table->timestamps();

		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	    });
    }
}
