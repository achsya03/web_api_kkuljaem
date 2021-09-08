<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('id_subs')->unsigned()->nullable();
            $table->char('stat_pembayaran',1);
            $table->string('snap_token');
            $table->datetime('tgl_pembayaran')->nullable();
            $table->string('uuid');
            $table->index(['uuid']);
            $table->foreign('id_subs')->references('id')->on('subs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
}
