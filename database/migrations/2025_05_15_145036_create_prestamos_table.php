<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestamosTable extends Migration
{
    public function up()
    {
        Schema::create('prestamo', function (Blueprint $table) {
            $table->id('id_prestamo');
            $table->unsignedBigInteger('id_libro');
            $table->unsignedBigInteger('id_usuario');
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion');
            $table->date('fecha_entrega_real')->nullable();
            // Incluye los nuevos valores en el enum:
            $table->enum('estado', ['pendiente', 'activo', 'entregado', 'retraso', 'denegado'])->default('pendiente');
            $table->timestamps();

            $table->foreign('id_libro')->references('id_libro')->on('libro');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestamo');
    }
}
