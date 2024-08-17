<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // Campo ID
            $table->string('name'); // Nome do status
            $table->timestamps();
        });

        // Inserir status padrão
        DB::table('statuses')->insert([
            ['name' => 'Disponível', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ocupado', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
