<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComandasTable extends Migration
{
    public function up()
    {
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->enum('status', ['open', 'closed'])->default('open'); // Status da comanda
            $table->decimal('valor_comanda', 8, 2)->default(0); // Valor total da comanda
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comandas');
    }
}
