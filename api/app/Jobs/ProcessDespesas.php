<?php

namespace App\Jobs;

use App\Models\Despesas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessDespesas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $deputy = $this->data;

        if (!isset($deputy["id"])) {
            Log::error('Deputado sem ID recebido na Job!', ['data' => $deputy]);
            return;
        }

        $id = $deputy["id"];
        $response = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados/{$id}/despesas");

        if ($response->failed()) {
            Log::error("Erro ao buscar despesas do deputado {$id}");
            return;
        }

        if (!isset($response['dados']) || empty($response['dados'])) {
            Log::warning("Nenhuma despesa encontrada para o deputado {$id}");
            return;
        }

        DB::transaction(function () use ($response, $id) {
            // Busca todos os codDocumento já existentes para o deputado
            $existing = Despesas::where('id_deputado', $id)
                ->pluck('codDocumento')
                ->toArray();

            foreach ($response["dados"] as $item) {
                $codDocumento = $item['codDocumento'] ?? null;
                if ($codDocumento && in_array($codDocumento, $existing)) {
                    continue; // Já existe, pula para o próximo
                }
                Despesas::create([
                    'ano'               => $item['ano'] ?? null,
                    'mes'               => $item['mes'] ?? null,
                    'tipoDespesa'       => $item['tipoDespesa'] ?? null,
                    'codDocumento'      => $codDocumento,
                    'tipoDocumento'     => $item['tipoDocumento'] ?? null,
                    'codTipoDocumento'  => $item['codTipoDocumento'] ?? null,
                    'dataDocumento'     => $item['dataDocumento'] ?? null,
                    'numDocumento'      => $item['numDocumento'] ?? null,
                    'valorDocumento'    => $item['valorDocumento'] ?? null,
                    'urlDocumento'      => $item['urlDocumento'] ?? null,
                    'nomeFornecedor'    => $item['nomeFornecedor'] ?? null,
                    'cnpjCpfFornecedor' => $item['cnpjCpfFornecedor'] ?? null,
                    'valorLiquido'      => $item['valorLiquido'] ?? null,
                    'valorGlosa'        => $item['valorGlosa'] ?? null,
                    'numRessarcimento'  => $item['numRessarcimento'] ?? null,
                    'codLote'           => $item['codLote'] ?? null,
                    'parcela'           => $item['parcela'] ?? null,
                    'id_deputado'       => $id,
                ]);
            }
        });

        Log::info("Despesas do deputado {$id} registradas com sucesso");
    }
}
