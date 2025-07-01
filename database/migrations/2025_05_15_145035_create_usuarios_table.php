<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellido', 100)->nullable();
            $table->string('dni', 8)->unique();
            $table->string('codigo_estudiante', 20)->unique();
            $table->string('email', 150)->unique();

            // ✅ Sin default: Laravel puede asignar la fecha en código (ahora()->toDateString())
            $table->date('fecha_registro')->nullable();

            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}

