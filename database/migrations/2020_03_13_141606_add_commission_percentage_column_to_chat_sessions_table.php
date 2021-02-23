<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionPercentageColumnToChatSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->integer('commission_percentage')->after('price_per_minute');
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
	        $table->dropColumn('commission_percentage');
        });
    }
}
