<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatPausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_pauses', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('chat_session_id')->unsigned();
	        $table->timestamp('ended_at')->nullable();
	        $table->string('reason');
            $table->timestamps();

	        $table->foreign('chat_session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_pauses');
    }
}
