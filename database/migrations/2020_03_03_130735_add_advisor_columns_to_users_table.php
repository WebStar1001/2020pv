<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvisorColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
	        $table->integer('horoscope_id')->unsigned()->nullable();
	        $table->integer('master_service_id')->unsigned()->nullable();
	        $table->text('experience')->nullable();
	        $table->text('qualification')->nullable();
	        $table->boolean('subscribed')->default(0);
	        $table->boolean('approved')->default(0);
	        $table->boolean('blocked')->default(0);

	        $table->foreign('horoscope_id')->references('id')->on('horoscopes')->onDelete('set null');
	        $table->foreign('master_service_id')->references('id')->on('services')->onDelete('set null');
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
	        $table->dropForeign('users_horoscope_id_foreign');
	        $table->dropForeign('users_master_service_id_foreign');

	        $table->dropColumn('horoscope_id');
	        $table->dropColumn('master_service_id');
	        $table->dropColumn('experience');
	        $table->dropColumn('qualification');
	        $table->dropColumn('subscribed');
	        $table->dropColumn('approved');
	        $table->dropColumn('blocked');
        });
    }
}
