<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBalanceBeforeAfterColumnsToPaypalPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_payments', function (Blueprint $table) {
	        $table->decimal('balance_after', 15, 10)->after('amount');
	        $table->decimal('balance_before', 15, 10)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paypal_payments', function (Blueprint $table) {
	        $table->dropColumn('balance_after');
	        $table->dropColumn('balance_before');
        });
    }
}
