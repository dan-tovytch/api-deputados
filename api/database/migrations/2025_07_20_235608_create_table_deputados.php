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
        Schema::create('deputados', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('nomeCivil');
            $table->string('nomeEleitoral');
            $table->string('siglaPartido');
            $table->string('siglaUf', 2);
            $table->integer('idLegislatura');
            $table->string('urlFoto')->nullable();
            $table->string('emailGabinete')->nullable();
            $table->string('telefoneGabinete')->nullable();
            $table->string('situacao');
            $table->string('condicaoEleitoral');
            $table->string('cpf', 11)->unique();
            $table->char('sexo', 1);
            $table->string('urlWebsite')->nullable();
            $table->date('dataNascimento');
            $table->date('dataFalecimento')->nullable();
            $table->string('ufNascimento', 2);
            $table->string('municipioNascimento');
            $table->string('escolaridade')->nullable();
            $table->json('redeSocial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados');
    }
};
