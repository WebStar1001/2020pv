<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountIdColumnToDiscountsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts_histories', function (Blueprint $table) {
	        $table->integer('discount_id')->unsigned()->nullable()->after('id');

	        $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts_histories', function (Blueprint $table) {
            $table->dropColumn('discount_id');
        });
    }
}
