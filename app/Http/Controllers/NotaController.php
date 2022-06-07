<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Services\NotaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotaController extends Controller
{
    private NotaService $notaService;

    /**
     * @OA\Tag(
     *     name="Notas",
     *     description="CRU dos estabelecimentos"
     * )
     */
    public function __construct(NotaService $notaService)
    {
        $this->notaService = $notaService;
    }

    /**
     * @OA\Get(
     *   path="/nota/estabelecimento",
     *   tags={"Notas"},
     *   summary="Lista as notas do usuário",
     *   description="Lista todas as notas do usuario cadastrada no sistema",
     *   @OA\Response(
     *     response=200,
     *     description="Ok"
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Error"
     *   )
     * )
     */
    /**
     * @OA\Get(
     *   path="nota/estabelecimento/{id}",
     *   tags={"Notas"},
     *   summary="Lista as notas de um estabelecimento em específico",
     *   description="Lista as notas de um estabelecimento em específico, de acordo com o Id do estabelecimento",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id do estabelecimento",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="ok"
     *   )
     * )
     */
    public function index(string $id = null): JsonResponse
    {
        $notas = $this->notaService->list($id);
        return response()->json($notas);
    }

    /**
     * @OA\Post(
     *   path="/nota",
     *   tags={"Notas"},
     *   summary="Cria uma nova nota ou atualiza a nota",
     *   description="Inseri uma nota se o estabelecimento não tiver nenhuma nota inserida pelo usuario, ou atualiza, caso a nota ja exista",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *               @OA\Property(property="id", type="integer", readOnly="true"),
     *               @OA\Property(property="user_id", type="integer", description="Referência do usuário"),
     *               @OA\Property(property="estabelecimento_id", type="integer", description="Referencia do estabelecimento"),
     *               @OA\Property(property="nota", type="number", description="Nota do estabelecimento, permitido apenas notas de 0 a 5")
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Created/Updated"
     *   ),
     *  @OA\Response(
     *     response="404",
     *     description="Estabelecimento não encontrado"
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
    public function storeOrUpdate(Request $request): JsonResponse
    {
        $data = array_merge($request->all(), ['user_id' => Auth::id()]);

        $validator = Validator::make($data, [
            'nota'               => 'required|numeric|between:0,5',
            'comment'            => 'string|required',
            'user_id'            => 'required',
            'estabelecimento_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $nota = $this->notaService->createOrUpdate($data);
        return response()->json($nota, 201);
    }

    /**
     * @OA\Get(
     *   path="nota/estabelecimento/{id}/geral",
     *   tags={"Notas"},
     *   summary="Lista a media das notas de um estabelecimento",
     *   description="Lista a media das notas de um estabelecimento",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id do estabelecimento",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="ok"
     *   )
     * )
     */
    public function notaGeral($id): JsonResponse
    {
        $estabelecimentos = $this->notaService->nota($id);
        if ($estabelecimentos->count() > 0) {
            $notaGeral = $estabelecimentos->sum('nota') / $estabelecimentos->count();
            return response()->json($notaGeral);
        }
        return response()->json(['info' => 'Nenhuma avaliação']);
    }
}
