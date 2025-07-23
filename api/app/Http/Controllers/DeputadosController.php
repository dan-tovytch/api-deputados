<?php

namespace App\Http\Controllers;

use App\Models\Deputados;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessDeputadosInfoJob;
use Exception;

class DeputadosController extends Controller
{
    public function index()
    {

        Log::info('Iniciando a atualização dos deputados.');


        $result = Http::get("https://dadosabertos.camara.leg.br/api/v2/deputados");

        if ($result->failed()) {
            Log::error('Falha ao buscar deputados da API.');

            return response()->json([
                "error" => true,
                "message" => "Nenhum dado encontrado!",
            ], 500);
        }


        $cpf = collect($result->json("dados"))->pluck("cpf");
        $consult = Deputados::whereIn("cpf", $cpf)->pluck("cpf")->toArray();
        $cpfsToRegister = $cpf->diff($consult);

        // recebe os id dos deputados a serem registrados, além do cpf
        $idsToRegister = collect($result->json("dados"))
            ->whereIn("cpf", $cpfsToRegister)
            ->pluck("id");

        foreach ($idsToRegister as $id) {
            ProcessDeputadosInfoJob::dispatch($id);
        }
    }


    public function ranking()
    {
        try {
            $ranking = DB::table('despesas')
                        ->join('deputados', 'deputados.id', '=', 'despesas.id_deputado')
                        ->select(
                            "deputados.nomeEleitoral",
                            "deputados.nomeCivil",
                            "deputados.emailGabinete",
                            "deputados.telefoneGabinete",
                            "deputados.sexo",
                            "deputados.escolaridade",
                            "deputados.dataNascimento",
                            "deputados.siglaPartido",
                            "deputados.siglaUf",
                            "deputados.urlFoto",
                            "deputados.redeSocial",
                            DB::raw("SUM(valorLiquido) as total")
                        )
                        ->groupBy(
                            'deputados.id',
                            'deputados.nomeEleitoral',
                            'deputados.nomeCivil',
                            'deputados.emailGabinete',
                            'deputados.telefoneGabinete',
                            'deputados.sexo',
                            'deputados.escolaridade',
                            'deputados.dataNascimento',
                            'deputados.siglaPartido',
                            'deputados.siglaUf',
                            'deputados.urlFoto',
                            'deputados.redeSocial'
                        )
                        ->orderByDesc('total')
                        ->paginate(10);

            return response()->json([
                "error" => false,
                "message" => "Sucesso",
                "data" => $ranking,
            ], 200);
        } catch (Exception $e) {
            Log::error("[DeputadosController][ranking] Erro ao buscar o ranking do deputados: ERROR:" . $e->getMessage());
            return response()->json([
                "error" => true,
                "message" => "Erro interno. Tente novamente",
            ], 500);
        }
    }
}
