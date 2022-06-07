<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Services\EstabelecimentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EstabelecimentoController extends Controller
{
    private EstabelecimentoService $estabelecimentoService;

    /**
     * @OA\Tag(
     *     name="Estabelecimentos",
     *     description="CRUD dos registros de estabelecimentos"
     * )
     */
    public function __construct(EstabelecimentoService $estabelecimentoService)
    {
        $this->estabelecimentoService = $estabelecimentoService;
    }

    /**
     * @OA\Get(
     *   path="/estabelecimento",
     *   tags={"Estabelecimentos"},
     *   summary="Lista todos os estabelecimentos",
     *   description="Lista todas os estabelecimentos cadastrados no sistema",
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
     *   path="/estabelecimento/{id}",
     *   tags={"Estabelecimentos"},
     *   summary="Lista um estabelecimento em específico",
     *   description="Lista um estabelecimento em específico, de acordo com o Id",
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
     *     response=404,
     *     description="Estabelecimento não encontrado"
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
    public function index(string $estabelecimentoId = null): JsonResponse
    {
        $estabelecimentos = $this->estabelecimentoService->list($estabelecimentoId);
        return response()->json($estabelecimentos);
    }

    /**
     * @OA\Post(
     *   path="/estabelecimento",
     *   tags={"Estabelecimentos"},
     *   summary="Cria um novo estabelecimento",
     *   description="Cadastra um novo estabelecimento no sistema, apenas administradores podem acessar esta rota",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *               @OA\Property(property="id", type="integer", readOnly="true"),
     *               @OA\Property(property="razao_social", type="string", description="Nome do estabelecimento"),
     *               @OA\Property(property="possui_bilhar", type="boolean", description="Se o estabelecimento possui bilhar"),
     *               @OA\Property(property="possui_happyHour", type="boolean", description="Se o estabelecimento possui happy hour"),
     *               @OA\Property(property="paga_cover", type="boolean", description="Se o estabelecimento cobra cover"),
     *               @OA\Property(property="hora_abertura", type="string", description="Horario em que o estabelecimento abre"),
     *               @OA\Property(property="hora_fechamento", type="string", description="Horario em que o estabelecimento fecha"),
     *               @OA\Property(property="possui_delivery", type="boolean", description="Se o estabelecimento possui entrega de lanches/bebida"),
     *               @OA\Property(property="outras_informacoes", type="string", description="Outras informações consideradas importantes para p estabelecimento"),
     *               @OA\Property(property="CEP", type="string", description="CEP do estabelecimento"),
     *               @OA\Property(property="rua", type="string", description="Rua do estabelecimento"),
     *               @OA\Property(property="bairro", type="string", description="Bairro do estabelecimentoo"),
     *               @OA\Property(property="cidade", type="string", description="Cidade do estabelecimento"),
     *               @OA\Property(property="estado", type="string", description="Estado do estabelecimento"),
     *               @OA\Property(property="celular", type="string", description="Numero de telefone do estabelecimento"),
     *               @OA\Property(property="numero", type="integer", description="Numero do estabelecimento")

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
     */
    public function store(Request $request): JsonResponse
    {
        $data = array_merge($request->all(), ['user_id' => Auth::id()]);

        $validator = Validator::make($data, [
            'razao_social'          => 'required|string',
            'possui_bilhar'         => 'required|boolean',
            'possui_happyHour'      => 'required|boolean',
            'paga_cover'            => 'required|boolean',
            'hora_abertura'         => 'required|date_format:H:i',
            'hora_fechamento'       => 'required|date_format:H:i',
            'possui_delivery'       => 'required|boolean',
            'outras_informacoes'    => 'string',
            'possui_musica_ao_vivo' => 'required|boolean',
            'user_id'               => 'required',
            'CEP'                   => 'required|string',
            'rua'                   => 'required|string',
            'bairro'                => 'required|string',
            'cidade'                => 'required|string',
            'estado'                => 'required|string',
            'celular'               => 'required',
            'numero'                => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $estabelecimento = $this->estabelecimentoService->create($data);
        return response()->json($estabelecimento, 201);
    }

    /**
     * @OA\Put(
     *   path="/estabelecimento/{id}",
     *   tags={"Estabelecimentos"},
     *   summary="Atualizar o estabelecimento, apenas administradores tem acesso a essa rota",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id do estabelecimento",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              example={"possui_bilhar": "false", "possui_delivery": "true", "outras_informacoes": "nao abre nos domingos"}
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
     *     description="Estabelecimento não encontrado"
     *   )
     * )
     */
    /**
     * @throws NotFoundException
     */
    public function update($id, Request $request): JsonResponse
    {
        $data = array_merge($request->all(), ['user_id' => Auth::id()]);

        $validator = Validator::make($data, [
            'razao_social'       => 'string',
            'possui_bilhar'      => 'boolean',
            'possui_happyHour'   => 'boolean',
            'paga_cover'         => 'boolean',
            'hora_abertura'      => 'date_format:H:i',
            'hora_fechamento'    => 'date_format:H:i',
            'possui_delivery'    => 'boolean',
            'nota_geral'         => 'numeric',
            'outras_informações' => 'string',
            'user_id'            => 'required',
            'CEP'                => 'string',
            'rua'                => 'string',
            'bairro'             => 'string',
            'cidade'             => 'string',
            'estado'             => 'string',
            'celular'            => 'string',
            'numero'             => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors', 'errors' => $validator->errors()], 422);
        }

        $estabelecimento = $this->estabelecimentoService->update($id, $data);
        return response()->json($estabelecimento, 201);
    }

    /**
     * @OA\Delete(
     *   path="/estabelecimento/{id}",
     *   tags={"Estabelecimentos"},
     *   summary="Deletar estabelecimento",
     *   description="Deletar estabelecimento,apenas administradores possuem acesso a essa rota",
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
     *     response=204,
     *     description="Ok"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Estabelecimento nao encontrado"
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
        $this->estabelecimentoService->delete($id);
        return response()->json([], 204);
    }
}
