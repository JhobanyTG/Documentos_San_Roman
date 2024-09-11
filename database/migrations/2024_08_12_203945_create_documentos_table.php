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
            $table->string('titulo', 255);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sub_usuarios_id')->nullable();
            $table->unsignedBigInteger('tipodocumento_id');
            $table->text('descripcion', 1500);
            $table->string('archivo', 254)->unique();
            $table->string('estado', 20);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('sub_usuarios_id')->references('id')->on('subusuarios')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tipodocumento_id')->references('id')->on('tipodocumento')->onDelete('cascade')->onUpdate('cascade');

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
