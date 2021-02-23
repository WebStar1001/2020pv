<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBalanceColumnsToChatSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
	        $table->decimal('advisor_balance_after', 15, 10)->nullable()->after('duration');
	        $table->decimal('advisor_balance_before', 15, 10)->nullable()->after('duration');
	        $table->decimal('customer_balance_after', 15, 10)->nullable()->after('duration');
	        $table->decimal('customer_balance_before', 15, 10)->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropColumn('advisor_balance_after');
	        $table->dropColumn('advisor_balance_before');
	        $table->dropColumn('customer_balance_after');
	        $table->dropColumn('customer_balance_before');
        });
    }
}
