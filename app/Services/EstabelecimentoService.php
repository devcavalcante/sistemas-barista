<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Repositories\EstabelecimentoRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class EstabelecimentoService
{
    /**
     * @var EstabelecimentoRepository
     */
    private EstabelecimentoRepository $estabelecimentoRepository;

    /**
     * @param EstabelecimentoRepository $estabelecimentoRepository
     */
    public function __construct(
        EstabelecimentoRepository $estabelecimentoRepository
    ) {
        $this->estabelecimentoRepository = $estabelecimentoRepository;
    }

    /**
     * @throws NotFoundException
     */
    public function list(string $id = null): Model|Collection|Builder|array
    {
        if ($id == null) {
            return $this->estabelecimentoRepository->listAll();
        }
        return $this->estabelecimentoRepository->findOneByIdOrFail($id);
    }

    public function create($data): Model|Builder
    {
        return $this->estabelecimentoRepository->create($data);
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $data): Model|Builder
    {
        return $this->estabelecimentoRepository->update($id, $data);
    }

    /**
     * @throws NotFoundException
     */
    public function delete($id)
    {
        return $this->estabelecimentoRepository->destroy($id);
    }
}
