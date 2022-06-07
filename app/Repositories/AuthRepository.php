<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    /**
     * @var Builder | Model
     */
    protected Builder|Model $model;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function auth($data): Model|Builder|null
    {
        return $this->model->where('email', $data['email'])->first();
    }

    public function logout(): ?Authenticatable
    {
        return Auth::user();
    }
}
