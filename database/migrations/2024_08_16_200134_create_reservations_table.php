<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('quarto_id')->constrained('quartos')->onDelete('cascade');
            $table->timestamp('horario_entrada');
            $table->integer('horas_contratadas');
            $table->decimal('valor_final', 8, 2);
            $table->timestamp('horario_saida');
            $table->enum('status', ['open', 'close'])->default('open'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
