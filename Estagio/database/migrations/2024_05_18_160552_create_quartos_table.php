<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartosTable extends Migration
{
    public function up()
    {
        Schema::create('quartos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->time('hora_entrada');
            $table->time('hora_saida');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quartos');
    }
}
