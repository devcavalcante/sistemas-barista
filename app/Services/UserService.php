<?php

namespace App\Services;

use App\Exceptions\AgeException;
use App\Exceptions\NotFoundException;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws NotFoundException
     */
    public function listUser(string $id = null): Model|Collection|Builder|array
    {
        if ($id == null) {
            return $this->userRepository->listWithTrashed();
        }
        return $this->userRepository->findOneByIdOrFail($id);
    }

    /**
     * @throws AgeException
     */
    public function create($data): Model|Builder
    {
        $data['password'] = Hash::make($data['password']);
        if ($data['idade'] < 18) {
            return throw new AgeException();
        }
        if (Auth::check() && Auth::user()->role_id == 2) {
            $data['role_id'] = 2;
            return $this->userRepository->create($data);
        }
        $data['role_id'] = 1;
        return $this->userRepository->create($data);
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $data): Model|Builder
    {
        return $this->userRepository->update($id, $data);
    }

    /**
     * @throws NotFoundException
     */
    public function delete($id): Model|Builder
    {
        if (Auth::user()->role_id == 2) {
            return $this->userRepository->destroy($id);
        }

        return $this->userRepository->destroy(Auth::id());
    }

    public function restore($id)
    {
        return $this->userRepository->restore($id);
    }

    /**
     * @throws NotFoundException
     */
    public function changePassword(&$data): Model|Collection|Builder|array
    {
        $data['password'] = Hash::make($data['new_password']);
        return $this->userRepository->update(Auth::id(), $data);
    }
}
