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
        Schema::create('subusuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_gerencia_id');
            $table->unsignedBigInteger('usuario_id');
            $table->string('cargo', 150);
            $table->timestamps();

            $table->foreign('sub_gerencia_id')->references('id')->on('subgerencias')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subusuarios');
    }
};
