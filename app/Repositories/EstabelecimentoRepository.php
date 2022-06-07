<?php

namespace App\Repositories;

use App\Models\Estabelecimento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EstabelecimentoRepository extends AbstractRepository
{
    /**
     * @var Model | Builder
     */
    protected Builder|Model $model;

    /**
     * EstabelecimentoRepository constructor.
     * @param Estabelecimento $model
     */
    public function __construct(Estabelecimento $model)
    {
        $this->model = $model;
    }
}
