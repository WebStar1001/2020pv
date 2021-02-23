<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_messages', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('email_subject_id')->unsigned();
	        $table->integer('sender_id')->unsigned();
	        $table->integer('receiver_id')->unsigned();
	        $table->text('message')->nullable();
	        $table->integer('invoice_amount')->nullable();
	        $table->boolean('invoice_accepted')->default(0);
	        $table->boolean('invoice_rejected')->default(0);
	        $table->boolean('invoice_cancelled')->default(0);
	        $table->boolean('read_status')->default(0);
            $table->timestamps();

	        $table->foreign('email_subject_id')->references('id')->on('email_subjects')->onDelete('cascade');
	        $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_messages');
    }
}
