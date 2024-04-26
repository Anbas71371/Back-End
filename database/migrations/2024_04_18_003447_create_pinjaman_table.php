<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamanTable extends Migration
{
    public function up()
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->double('besar_pinjaman');
            $table->double('jasa')->default(2000);
            $table->double('total')->nullable();
            $table->unsignedBigInteger('user_id'); // Tambahkan kolom 'user_id'
            $table->foreign('user_id')->references('id')->on('users'); // Kunci asing ke tabel 'users'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pinjaman');
    }
}

