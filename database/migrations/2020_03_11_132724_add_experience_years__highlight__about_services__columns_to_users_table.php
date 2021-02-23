<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExperienceYearsHighlightAboutServicesColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('users', function (Blueprint $table) {
		    $table->integer('experience_years')->nullable();
		    $table->text('highlight')->nullable();
		    $table->text('about_services')->nullable();
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
		    $table->dropColumn('experience_years');
		    $table->dropColumn('highlight');
		    $table->dropColumn('about_services');
	    });
    }
}
