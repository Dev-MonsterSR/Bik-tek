<?php
// filepath: database/migrations/xxxx_xx_xx_xxxxxx_create_administradores_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministradoresTable extends Migration
{
    public function up()
    {
        Schema::create('administrador', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('usuario', 50)->unique();
            $table->string('clave', 255);
            $table->string('nombre', 100)->nullable();
            $table->string('correo', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrador');
    }
}
