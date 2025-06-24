<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrosTable extends Migration
{
    public function up()
    {
        Schema::create('libro', function (Blueprint $table) {
            $table->id('id_libro');

            // Nuevo campo solicitado
            $table->string('codigo', 50)->nullable();

            // Campos originales
            $table->string('titulo', 255);
            $table->string('autor', 150)->nullable();
            $table->string('editorial', 150)->nullable();
            $table->year('anio_publicacion')->nullable();
            $table->unsignedBigInteger('categoria_id')->nullable();

            // Nuevos campos solicitados
            $table->integer('cantidad')->default(1);
            $table->integer('disponibles')->default(1);
            $table->string('portada')->nullable();

            // Campo de estado original
            $table->enum('estado', ['disponible', 'No disponible'])
                  ->default('disponible');

            $table->timestamps();

            // FK a categoría
            $table->foreign('categoria_id')
                  ->references('id_categoria')
                  ->on('categoria')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        // Al hacer rollback se elimina toda la tabla
        Schema::dropIfExists('libro');
    }
}
