<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBalanceColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('users', function (Blueprint $table) {
		    $table->decimal('balance', 15, 10)->default(0)->change();
	    });

	    Schema::table('payment_histories', function (Blueprint $table) {
		    $table->decimal('balance_before', 15, 10)->change();
		    $table->decimal('balance_after', 15, 10)->change();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('users', function (Blueprint $table) {
		    $table->integer('balance')->default(0)->change();
	    });

	    Schema::table('payment_histories', function (Blueprint $table) {
		    $table->integer('balance_before')->change();
		    $table->integer('balance_after')->change();
	    });
    }
}
