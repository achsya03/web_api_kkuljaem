<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('url_foto')->nullable();
            $table->string('foto_id')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('device_id')->nullable();
            $table->string('web_token')->nullable();
            $table->char('jenis_pengguna',1)->nullable();
            $table->char('jenis_akun',1)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('tgl_langganan_akhir')->nullable();
            $table->string('uuid')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            //$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index(['web_token','uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
