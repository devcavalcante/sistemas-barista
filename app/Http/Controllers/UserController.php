<?php

namespace App\Http\Controllers;

use App\Exceptions\AgeException;
use App\Exceptions\NotFoundException;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentação Barista",
 *      description="Documentação das rotas do sistema"
 * )
 *
 */

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Tag(
     *     name="Users",
     *     description="CRUD dos registros de usuários"
     * )
     */
    /**
     * @OA\Get(
     *   path="/users",
     *   tags={"Users"},
     *   summary="Lista todas os usuários",
     *   description="Lista todas os usuários do sistema, apenas administradores podem acessar essa rota",
     *   @OA\Response(
     *     response=200,
     *     description="Ok"
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="Unauthorized"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     */
    /**
     * @OA\Get(
     *   path="/users/{userId}",
     *   tags={"Users"},
     *   summary="Lista um usuário em específico",
     *   description="Lista um usuário em específico",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id do usuário",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Usuário não encontrado"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="ok"
     *   )
     * )
     */
    /**
     * @throws NotFoundException
     */
    public function list(string $id = null): JsonResponse
    {
        $user = $this->userService->listUser($id);
        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     *   path="/users",
     *   tags={"Users"},
     *   summary="Criar novo usuário",
     *   description="Criar novo usuário",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *               @OA\Property(property="id", type="integer", readOnly="true"),
     *               @OA\Property(property="role_id", type="integer", description="Referência do tipo de usuário"),
     *               @OA\Property(property="username", type="string", description="Nickname do usuário"),
     *               @OA\Property(property="name", type="string", description="Nome do usuário"),
     *               @OA\Property(property="email", type="string", description="Email do usuário"),
     *               @OA\Property(property="password", type="string", description="Senha do usuário"),
     *               @OA\Property(property="idade", type="integer", description="Idade do usuário, usuário deve ser maior de 18 anos")
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     * @throws AgeException
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email'    => 'required|email|unique:users',
            'password' => 'required',
            'name'     => 'required|string',
            'username' => 'required|string|unique:users',
            'idade'    => 'required|numeric|min:18',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $user = $this->userService->create($request->all());
        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *   path="/users",
     *   tags={"Users"},
     *   summary="Atualizar usuário, apenas o proprio usuario pode alterar seu usuario",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              example={"username": "Nickname do usuario", "name": "Nome do usuário", "email": "email do usuário"}
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Atualizado"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   ),
     *  @OA\Response(
     *     response="404",
     *     description="Usuario não encontrado"
     *   )
     * )
     */
    /**
     * @throws NotFoundException
     */
    public function update(Request $request): JsonResponse
    {
        $user = $this->userService->update(Auth::id(), $request->all());
        return response()->json($user, 201);
    }

    /**
     * @OA\Delete(
     *   path="/users/{userId}",
     *   tags={"Users"},
     *   summary="Deletar usuário",
     *   description="Deletar usuário, administradores podem desabilitar um usuario e um usuario pode excluir sua conta",
     *   @OA\Parameter(
     *     name="userId",
     *     in="path",
     *     description="Id do usuario",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=204,
     *     description="Ok"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Usuario nao encontrado"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     */
    /**
     * @throws NotFoundException
     */
    public function destroy($id): JsonResponse
    {
        $this->userService->delete($id);
        return response()->json([], 204);
    }

    /**
     * @OA\Post(
     *   path="/users/{userId}",
     *   tags={"Users"},
     *   summary="Restaurar um usuário",
     *   description="Habilitar um usuário depois de excluido, somente administradores podem habilitar um usuario",
     *   @OA\Parameter(
     *     name="userId",
     *     in="path",
     *     description="Id do usuario",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Ok"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Usuario nao encontrado"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     */
    public function restore($id): JsonResponse
    {
        $user = $this->userService->restore($id);
        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *   path="/users/changePassword",
     *   tags={"Users"},
     *   summary="Alterar a senha",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              example={"old_password": "Senha antiga", "new_password": "Nova senha"}
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Atualizado"
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Erro na validação dos campos"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     */
    /**
     * @throws NotFoundException
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'old_password' => [
                'required',
        function ($attribute, $value, $fail) {
            if (!Hash::check($value, Auth::user()->password)) {
                $fail('Old Password didn\'t match');
            }
        },
            ],
            'new_password' => 'required|string|different:old_password',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $user =  $this->userService->changePassword($data);

        return response()->json($user, 201);
    }
}
