<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Tag(
     *     name="Autenticação",
     *     description="Autenticação do sistema"
     * )
     */

    /**
     * @OA\Post(
     *   path="/login",
     *   tags={"Autenticação"},
     *   summary="Fazer login",
     *   description="Fazer login para acessar o sistema",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *               @OA\Property(property="email", type="string", description="email de login do usuario"),
     *               @OA\Property(property="password", type="string", description="senha do usuario")
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Logado"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   ),
     *  @OA\Response(
     *     response="422",
     *     description="erro de validaçao"
     *   )
     * )
     */
    public function authenticate(Request $request): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
                'email'    => 'required|email',
                'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $fields = $request->only(['email', 'password']);
        $result = $this->authService->authenticate($fields);

        return response()->json($result, $result['code']);
    }

    /**
     * @OA\Post(
     *   path="/logout",
     *   tags={"Autenticação"},
     *   summary="Fazer logout",
     *   description="Fazer logout para sair do sistema",
     *   @OA\Response(
     *     response=200,
     *     description="deslogado"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     */
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }
}
