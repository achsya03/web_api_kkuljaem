<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('detail_students', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('id_users')->unsigned()->nullable();
            $table->string('alamat')->nullable();
            $table->char('jenis_kel')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('uuid');
            $table->index(['uuid']);
            $table->foreign('id_users')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_students');
    }
}
