<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutCronHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_cron_histories', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('payout_id')->unsigned();
            $table->timestamps();

	        $table->foreign('payout_id')->references('id')->on('payouts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payout_cron_histories');
    }
}
