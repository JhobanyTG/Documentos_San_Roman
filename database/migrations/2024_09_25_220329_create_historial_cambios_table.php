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
        Schema::create('historial_cambios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id')->constrained()->onDelete('cascade');
            $table->string('estado_anterior');
            $table->string('estado_nuevo');
            $table->text('descripcion');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sub_usuarios_id')->nullable();
            $table->timestamps(); // Para registrar cuándo se hizo el cambio
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('sub_usuarios_id')->references('id')->on('subusuarios')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_cambios');
    }
};
