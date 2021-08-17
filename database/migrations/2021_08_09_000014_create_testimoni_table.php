<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimoniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('id_class')->unsigned()->nullable();
            $table->BigInteger('id_user')->unsigned()->nullable();
            $table->date('tgl_testimoni')->nullable();
            $table->text('testimoni')->nullable();
            $table->string('uuid');
            $table->index(['uuid']);
            $table->foreign('id_class')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testimoni');
    }
}
