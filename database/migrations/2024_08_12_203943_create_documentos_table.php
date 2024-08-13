<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_usuarios_id');
            $table->string('titulo', 255);
            $table->text('descripcion', 1500);
            $table->string('archivo', 254)->unique();
            $table->string('estado', 20);
            $table->timestamps();

            $table->foreign('sub_usuarios_id')->references('id')->on('subusuarios')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
