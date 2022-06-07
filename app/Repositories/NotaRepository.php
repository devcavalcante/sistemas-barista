<?php

namespace App\Repositories;

use App\Models\Nota;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotaRepository extends AbstractRepository
{
    /**
     * @var Builder|Model|Nota
     */
    protected Builder|Model|Nota $model;

    /**
     * @param Nota $model
     */
    public function __construct(Nota $model)
    {
        $this->model = $model;
    }

    public function find($estabelecimentoId): Builder
    {
        return $this->model->where('user_id', Auth::id())
            ->where('estabelecimento_id', $estabelecimentoId);
    }

    public function findByUser(): Builder
    {
        return $this->model->where('user_id', Auth::id());
    }

    public function findByEstabelecimento($estabelecimentoId): Collection|array
    {
        return $this->model->where('estabelecimento_id', $estabelecimentoId)->get();
    }
}
