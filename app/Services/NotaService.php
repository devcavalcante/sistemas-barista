<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Estabelecimento;
use App\Repositories\NotaRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NotaService
{
    /**
     * @var NotaRepository
     */
    private NotaRepository $notaRepository;

    /**
     * @param NotaRepository $notaRepository
     */
    public function __construct(
        NotaRepository $notaRepository
    ) {
        $this->notaRepository = $notaRepository;
    }

    public function list(?string $estabelecimentoId): object|array|null
    {
        if ($estabelecimentoId) {
            return $this->notaRepository->find($estabelecimentoId)->first();
        }
        return $this->notaRepository->findByUser()->get();
    }

    /**
     * @throws NotFoundException
     */
    public function createOrUpdate(array $data): Model|Builder
    {
        $nota = $this->notaRepository->find($data['estabelecimento_id'])->first();

        if ($nota) {
            return $this->notaRepository->update($nota->id, $data);
        }
        return $this->notaRepository->create($data);
    }

    public function nota($estabelecimentoId): Collection|array
    {
        return $this->notaRepository->findByEstabelecimento($estabelecimentoId);
    }
}
