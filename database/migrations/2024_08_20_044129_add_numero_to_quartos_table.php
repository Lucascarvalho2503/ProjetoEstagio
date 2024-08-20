<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroToQuartosTable extends Migration
{
    public function up()
    {
        Schema::table('quartos', function (Blueprint $table) {
            $table->integer('numero')->after('id'); // Adiciona a coluna 'numero' após a coluna 'id'
        });
    }

    public function down()
    {
        Schema::table('quartos', function (Blueprint $table) {
            $table->dropColumn('numero');
        });
    }
}
