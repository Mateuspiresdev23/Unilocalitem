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
        Schema::create('devolvidos', function (Blueprint $table) {
            $table->id('ID');
            $table->dateTime('DATAHORA');
            $table->bigInteger('IDUSUARIO')->unsigned();
            $table->bigInteger('IDPUBLICACAO')->unsigned();

            $table->foreign('IDUSUARIO')->references('ID')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('IDPUBLICACAO')->references('ID')->on('publicacoes')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolvidos');
    }
};
