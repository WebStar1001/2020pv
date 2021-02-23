<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewLeftColumnToEmailMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_messages', function (Blueprint $table) {
	        $table->boolean('review_left')->default(0)->after('read_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_messages', function (Blueprint $table) {
            $table->dropColumn('review_left');
        });
    }
}
