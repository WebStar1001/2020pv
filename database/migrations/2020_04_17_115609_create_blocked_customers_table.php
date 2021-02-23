<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockedCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocked_customers', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('customer_id')->unsigned();
	        $table->integer('advisor_id')->unsigned();
            $table->timestamps();

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
        Schema::dropIfExists('blocked_customers');
    }
}
