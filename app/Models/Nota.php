<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $fillable = [
        'id',
        'nota',
        'user_id',
        'estabelecimento_id',
        'comment'
    ];

    protected $table = 'notas';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estabelecimento(): BelongsTo
    {
        return $this->belongsTo(Estabelecimento::class);
    }
}
