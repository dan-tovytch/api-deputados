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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_deputado")->references("id")->on("deputados")->onDelete("cascade");
            $table->integer('ano');
            $table->tinyInteger('mes');
            $table->string('tipoDespesa');
            $table->bigInteger('codDocumento');
            $table->string('tipoDocumento');
            $table->integer('codTipoDocumento');
            $table->date('dataDocumento');
            $table->string('numDocumento');
            $table->decimal('valorDocumento', 12, 2);
            $table->string('urlDocumento')->nullable();
            $table->string('nomeFornecedor');
            $table->string('cnpjCpfFornecedor', 20);
            $table->decimal('valorLiquido', 12, 2);
            $table->decimal('valorGlosa', 12, 2);
            $table->string('numRessarcimento')->nullable();
            $table->bigInteger('codLote');
            $table->integer('parcela');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
