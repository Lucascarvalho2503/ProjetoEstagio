<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartosTable extends Migration
{
    public function up()
    {
        Schema::create('quartos', function (Blueprint $table) {
            $table->id(); // Campo ID
            $table->string('tipo_de_quarto'); // Tipo de quarto
            $table->decimal('valor_hora', 8, 2); // Valor por hora
            $table->foreignId('status_id')->nullable()->constrained('statuses')->onDelete('cascade'); // Chave estrangeira para Status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quartos');
    }
}
