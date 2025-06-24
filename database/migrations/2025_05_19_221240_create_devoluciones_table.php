<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id('id_devolucion');
            $table->unsignedBigInteger('id_prestamo');
            $table->date('fecha_devolucion');
            $table->string('estado_libro', 100);
            $table->timestamps();

            $table->foreign('id_prestamo')->references('id_prestamo')->on('prestamo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoluciones');
    }
};
