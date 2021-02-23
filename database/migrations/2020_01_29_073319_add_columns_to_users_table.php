<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
	        $table->boolean('email_confirmed')->default(0)->after('email');
	        $table->string('display_name')->nullable()->after('last_name');
	        $table->text('description')->nullable();
	        $table->string('avatar')->nullable();
	        $table->integer('price_per_minute')->nullable();
	        $table->integer('commission_percentage')->nullable();
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
	        $table->dropColumn('email_confirmed');
	        $table->dropColumn('display_name');
	        $table->dropColumn('description');
	        $table->dropColumn('avatar');
	        $table->dropColumn('price_per_minute');
	        $table->dropColumn('commission_percentage');
        });
    }
}
