<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deputados extends Model
{
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'nomeCivil',
        'nomeEleitoral',
        'siglaPartido',
        'siglaUf',
        'idLegislatura',
        'urlFoto',
        'emailGabinete',
        'telefoneGabinete',
        'situacao',
        'condicaoEleitoral',
        'cpf',
        'sexo',
        'urlWebsite',
        'dataNascimento',
        'dataFalecimento',
        'ufNascimento',
        'municipioNascimento',
        'escolaridade',
        'redeSocial',
    ];

    protected $casts = [
        'redeSocial' => 'array',
        'dataNascimento' => 'date',
        'dataFalecimento' => 'date',
    ];
}
