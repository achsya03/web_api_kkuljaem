<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan_teks')->nullable();
            $table->string('url_gambar')->nullable();
            $table->string('gambar_id')->nullable();
            $table->string('url_file')->nullable();
            $table->string('file_id')->nullable();
            $table->string('jawaban')->nullable();
            #$table->string('jenis_jawaban')->nullable();
            $table->string('uuid');
            $table->index(['uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
