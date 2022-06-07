<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

abstract class AbstractRepository
{
    /**
     * @var Model | Builder
     */
    protected Builder|Model $model;

    public function listAll(): Collection|array
    {
        return $this->model->get();
    }

    public function listWithTrashed(): array|Collection
    {
        return $this->model->withTrashed()->get();
    }

    /**
     * @throws NotFoundException
     */
    public function findOneByIdOrFail($id): Model|Collection|Builder|array
    {
        $model = $this->model->find($id);

        if (!$model) {
            throw new NotFoundException($this->model->getNotFoundMessage());
        }

        return $model;
    }

    public function create(array $data): Model|Builder
    {
        return $this->model->create($data);
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, array $data): Model|Collection|Builder|array
    {
        $register = $this->findOneByIdOrFail($id);

        $register->fill($data);

        $register->save();

        return $register;
    }

    /**
     * @throws NotFoundException
     */
    public function destroy(string $id)
    {
        return $this->findOneByIdOrFail($id)->delete();
    }

    public function restore(string $id)
    {
        return $this->model->withTrashed()->where('id', $id)->restore();
    }
}
