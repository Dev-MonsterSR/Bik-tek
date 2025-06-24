<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sanciones', function (Blueprint $table) {
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa')->after('tipo');
        });
    }

    public function down()
    {
        Schema::table('sanciones', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
