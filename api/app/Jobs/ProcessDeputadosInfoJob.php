<?php

namespace App\Jobs;

use App\Models\Deputados;
use Illuminate\Bus\Queueable;
use App\Jobs\ProcessDespesas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessDeputadosInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        Log::info("Iniciando processamento do deputado ID: {$this->id}");

        $response = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$this->id}");

        if ($response->successful()) {
            $data = $response->json("dados");

            Deputados::updateOrCreate(
                ['cpf' => $data['cpf']],
                [
                    'id'                    => $data['id'] ?? null,
                    'nomeCivil'             => $data['nomeCivil'] ?? null,
                    'nomeEleitoral'         => $data['ultimoStatus']['nomeEleitoral'] ?? null,
                    'siglaPartido'          => $data['ultimoStatus']['siglaPartido'] ?? null,
                    'siglaUf'               => $data['ultimoStatus']['siglaUf'] ?? null,
                    'idLegislatura'         => $data['ultimoStatus']['idLegislatura'] ?? null,
                    'urlFoto'               => $data['ultimoStatus']['urlFoto'] ?? null,
                    'emailGabinete'         => $data['ultimoStatus']['gabinete']['email'] ?? null,
                    'telefoneGabinete'      => $data['ultimoStatus']['gabinete']['telefone'] ?? null,
                    'situacao'              => $data['ultimoStatus']['situacao'] ?? null,
                    'condicaoEleitoral'     => $data['ultimoStatus']['condicaoEleitoral'] ?? null,
                    'cpf'                   => $data['cpf'] ?? null,
                    'sexo'                  => $data['sexo'] ?? null,
                    'urlWebsite'            => $data['urlWebsite'] ?? null,
                    'dataNascimento'        => $data['dataNascimento'] ?? null,
                    'dataFalecimento'       => $data['dataFalecimento'] ?? null,
                    'ufNascimento'          => $data['ufNascimento'] ?? null,
                    'municipioNascimento'   => $data['municipioNascimento'] ?? null,
                    'escolaridade'          => $data['escolaridade'] ?? null,
                    'redeSocial'            => $data['redeSocial'] ?? [],
                ]
            );

            Log::info("Deputado ID {$this->id} registrado/atualizado com sucesso.");

            ProcessDespesas::dispatch(['id' => $this->id]);
        } else {
            Log::error("Falha ao buscar dados do deputado ID: {$this->id}");
        }
    }
}
