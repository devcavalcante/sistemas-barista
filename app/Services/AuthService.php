<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class AuthService
{
    /**
     * @var AuthRepository
     */
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function authenticate(array $fields): array
    {
        try {
            $user = $this->authRepository->auth($fields);

            if (!$user) {
                throw new AuthorizationException('User does not exist', 401);
            }

            if (!Hash::check($fields['password'], $user->password)) {
                throw new AuthorizationException('Invalid credentials', 401);
            }

            $token = $this->authRepository->auth($fields)->createToken($this->authRepository->auth($fields));

            return [
                'access_token' => $token->accessToken,
                'expires_at'   => $token->token->expires_at,
                'user'         => $this->authRepository->auth($fields),
                'code'         => 200,
            ];
        } catch (AuthorizationException | Exception $exception) {
            return [
                'errors' => $exception->getMessage(),
                'code'   => $exception->getCode(),
            ];
        }
    }

    public function logout(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json('Usuario nao esta logado', 403);
        }
        $user = $this->authRepository->logout();
        $user->token()->revoke();
        return response()->json('Deslogado');
    }
}
