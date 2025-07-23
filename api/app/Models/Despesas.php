<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesas extends Model
{
    protected $table = 'despesas';

    protected $fillable = [
        'ano',
        'mes',
        'tipoDespesa',
        'codDocumento',
        'tipoDocumento',
        'codTipoDocumento',
        'dataDocumento',
        'numDocumento',
        'valorDocumento',
        'urlDocumento',
        'nomeFornecedor',
        'cnpjCpfFornecedor',
        'valorLiquido',
        'valorGlosa',
        'numRessarcimento',
        'codLote',
        'parcela',
        'id_deputado',
    ];

    protected $casts = [
        'dataDocumento' => 'date',
        'valorDocumento' => 'decimal:2',
        'valorLiquido' => 'decimal:2',
        'valorGlosa' => 'decimal:2',
    ];
}
