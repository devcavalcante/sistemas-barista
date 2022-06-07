<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estabelecimento extends Model
{
    protected $fillable = [
        'razao_social',
        'possui_bilhar',
        'possui_happyHour',
        'possui_delivery',
        'paga_cover',
        'hora_abertura',
        'hora_fechamento',
        'outras_informacoes',
        'possui_musica_ao_vivo',
        'user_id',
        'CEP',
        'rua',
        'bairro',
        'cidade',
        'estado',
        'celular',
        'numero',
    ];

    protected $table = 'estabelecimentos';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getNotFoundMessage(): string
    {
        return 'Estabelecimento não encontrado';
    }
}
