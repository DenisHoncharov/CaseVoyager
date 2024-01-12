<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/types",
     *     summary="Show all types",
     *     tags={"Types"},
     *     @OA\Response(response="200", description="Show all types", @OA\JsonContent(
     *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TypeResource"))
     *    )
     *   )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return TypeResource::collection(Type::all());
    }

    /**
     * @OA\Post(
     *     path="/api/types/create",
     *     summary="Create type",
     *     tags={"Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypeRequest")
     *     ),
     *     @OA\Response(response="200", description="Create type", @OA\JsonContent(
     *         ref="#/components/schemas/Type"
     *     )
     *   )
     * )
     *
     * @param TypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(TypeRequest $request)
    {
        $type = Type::create($request->validated());

        return response()->json($type);
    }

    /**
     * @OA\Get(
     *     path="/api/types/{type}",
     *     summary="Show type",
     *     tags={"Types"},
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="Type id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Show type", @OA\JsonContent(
     *         ref="#/components/schemas/Type"
     *     )
     *   )
     * )
     *
     * @param Request $request
     * @param Type $type
     * @return array
     */
    public function show(Request $request, Type $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
        ];
    }

    /**
     * @OA\Put(
     *     path="/api/types/{type}",
     *     summary="Update type",
     *     tags={"Types"},
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="Type id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypeRequest")
     *     ),
     *     @OA\Response(response="200", description="Update type", @OA\JsonContent(
     *         ref="#/components/schemas/Type"
     *     )
     *   )
     * )
     *
     * @param TypeRequest $request
     * @param Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TypeRequest $request, Type $type)
    {
        $type = $type->update($request->validated());

        return response()->json($type);
    }

    /**
     * @OA\Delete(
     *     path="/api/types/{type}",
     *     summary="Delete type",
     *     tags={"Types"},
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="Type id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Delete type", @OA\JsonContent(
     *         ref="#/components/schemas/Type"
     *     )
     *   )
     * )
     *
     * @param Type $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Type $type)
    {
        $type->delete();

        return response()->json();
    }
}
