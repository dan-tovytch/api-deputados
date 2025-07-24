<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW vw_ranking_despesas AS
            SELECT
                d.id_deputado,
                dep.nomeEleitoral,
                dep.nomeCivil,
                dep.emailGabinete,
                dep.telefoneGabinete,
                dep.sexo,
                dep.escolaridade,
                dep.dataNascimento,
                dep.siglaPartido,
                dep.siglaUf,
                dep.urlFoto,
                dep.redeSocial,
                SUM(d.valorLiquido) AS total
            FROM despesas d
            JOIN deputados dep ON dep.id = d.id_deputado
            GROUP BY
                d.id_deputado,
                dep.nomeEleitoral,
                dep.nomeCivil,
                dep.emailGabinete,
                dep.telefoneGabinete,
                dep.sexo,
                dep.escolaridade,
                dep.dataNascimento,
                dep.siglaPartido,
                dep.siglaUf,
                dep.urlFoto,
                dep.redeSocial
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_ranking_despesas");
    }
};
