<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('nominal');
            $table->string('bukti_pembayaran')->nullable();
            $table->tinyInteger('jenis_pembayaran')->comment('0 : DP, 1 : Lunas');
            $table->tinyInteger('status_pembayaran')->default(0)->comment('0 : blm dikonfirmasi, 1 : Dikonfirmasi');
            $table->bigInteger('bank_account_id')->unsigned();
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->dateTime('confirmed_at')->nullable();
            $table->string('kode_unik')->unique()->nullable();
            $table->bigInteger('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
