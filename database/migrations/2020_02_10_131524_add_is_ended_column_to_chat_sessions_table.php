<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsEndedColumnToChatSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->boolean('is_ended')->default(0)->after('advisor_balance_after');
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
            $table->dropColumn('is_ended');
        });
    }
}
