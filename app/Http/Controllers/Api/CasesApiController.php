<?php

namespace App\Http\Controllers\Api;

use App\Events\CaseOpenedEvent;
use App\Events\ReceiveItemFromCaseEvent;
use App\Events\UpdateUserBalanceEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CasesItemsRequest;
use App\Http\Requests\CasesRequest;
use App\Http\Requests\ExchangeOpenedItemsRequest;
use App\Http\Resources\CasesResource;
use App\Models\Cases;
use App\Models\Item;
use App\Models\OpenCaseResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CasesApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cases",
     *     summary="Show all cases",
     *     tags={"Cases"},
     *     @OA\Response(response="200", description="Show all cases", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CasesResource"))
     *     )
     *   )
     * )
     *
     * @param Request $request
     * @return CasesResource
     *
     */
    public function index(Request $request): JsonResource
    {
        return CasesResource::collection(Cases::all());
    }

    /**
     * @OA\Get(
     *     path="/api/cases/{case}",
     *     summary="Show case",
     *     tags={"Cases"},
     *     @OA\Parameter(
     *         name="case",
     *         in="path",
     *         description="Case id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Show case", @OA\JsonContent(
     *         @OA\Property (property="id", type="integer", readOnly="true", example="1"),
     *         @OA\Property (property="name", type="string", example="Case 1"),
     *         @OA\Property (property="type", type="object", ref="#/components/schemas/Type"),
     *         @OA\Property (property="price", type="float", example="1.00"),
     *         @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
     *         @OA\Property (property="description", type="string", example="Description"),
     *         @OA\Property (property="items", type="array", @OA\Items(ref="#/components/schemas/Item"))
     *      )
     *   )
     * )
     *
     * @param Request $request
     * @param Cases $case
     * @return JsonResponse
     *
     */
    public function show(Request $request, Cases $case): JsonResponse
    {
        return response()->json([
            'id' => $case->id,
            'name' => $case->name,
            'type' => $case->type,
            'image' => $case->image,
            'price' => $case->price,
            'description' => $case->description,
            'items' => $case->items()
                ->withPivot('drop_percentage')
                ->get()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cases/create",
     *     summary="Create case",
     *     tags={"Cases"},
     *     @OA\RequestBody(
     *         description="Create case object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CasesRequest")
     *     ),
     *     @OA\Response(response="200", description="Create case", @OA\JsonContent(
     *         @OA\Property (property="id", type="integer", readOnly="true", example="1"),
     *         @OA\Property (property="name", type="string", example="Case 1"),
     *         @OA\Property (property="type", type="object", ref="#/components/schemas/Type"),
     *         @OA\Property (property="price", type="float", example="1.00"),
     *         @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
     *         @OA\Property (property="description", type="string", example="Description"),
     *         @OA\Property (property="items", type="array", @OA\Items(
     *             @OA\Property (property="item_id", type="integer", example="1"),
     *             @OA\Property (property="drop_percentage", type="float", example="1.00")
     *         ))
     *      )
     *   )
     * )
     *
     * @param CasesRequest $request
     * @return JsonResponse
     *
     */
    public function create(CasesRequest $request): JsonResponse
    {
        $case = Cases::create($request->validatedExcept(['items']));

        if ($request->has('items')) {
            $this->syncItems($case, $request->validated(['items']));
        }

        return response()->json($case);
    }

    /**
     * @OA\Put(
     *     path="/api/cases/{case}/update",
     *     summary="Update case",
     *     tags={"Cases"},
     *     @OA\Parameter(
     *         name="case",
     *         in="path",
     *         description="Case id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update case object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CasesRequest")
     *     ),
     *     @OA\Response(response="200", description="Update case", @OA\JsonContent(
     *         @OA\Property (property="id", type="integer", readOnly="true", example="1"),
     *         @OA\Property (property="name", type="string", example="Case 1"),
     *         @OA\Property (property="type", type="object", ref="#/components/schemas/Type"),
     *         @OA\Property (property="price", type="float", example="1.00"),
     *         @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
     *         @OA\Property (property="description", type="string", example="Description"),
     *         @OA\Property (property="items", type="array", @OA\Items(
     *             @OA\Property (property="item_id", type="integer", example="1"),
     *             @OA\Property (property="drop_percentage", type="float", example="1.00")
     *         ))
     *      )
     *   )
     * )
     *
     * @param CasesRequest $request
     * @param Cases $case
     * @return JsonResponse
     *
     */
    public function update(CasesRequest $request, Cases $case): JsonResponse
    {
        $case->update($request->validatedExcept(['items']));

        if ($request->has('items')) {
            $this->syncItems($case, $request->validated(['items']));
        }

        return response()->json($case);
    }

    /**
     * @OA\Delete(
     *     path="/api/cases/{case}",
     *     summary="Delete case",
     *     tags={"Cases"},
     *     @OA\Parameter(
     *         name="case",
     *         in="path",
     *         description="Case id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Delete case", @OA\JsonContent(type="object"))
     * )
     *
     * @param Cases $case
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Cases $case): JsonResponse
    {
        $case->delete();

        return response()->json();
    }

    /**
     * @OA\Get(
     *     path="/api/cases/open/{case}",
     *     summary="Open case",
     *     tags={"Cases"},
     *     @OA\Parameter(
     *     name="case",
     *     in="path",
     *     description="Case id",
     *     required=true,
     *     @OA\Schema(
     *     type="integer",
     *     example="1"
     *    )
     *  ),
     *     @OA\Response(response="200", description="Open case", @OA\JsonContent(ref="#/components/schemas/Item"))
     * )
     *
     * @param Cases $case
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function openCase(Cases $case): JsonResponse
    {
        if (app()->make('getUserFromDBUsingAuth0')?->balance < $case->price) {
            return response()->json([
                'message' => 'You don\'t have enough money on your balance to open this case'
            ], 422);
        }

        $items = $case->items()
            ->wherePivot('drop_percentage', '>',  0)
            ->withPivot('drop_percentage')
            ->get();

        $selectedItem = null;

        $randomNumber = mt_rand(1, 1000000) / 10000; // Generate number between 0.0001 to 100

        $currentPercentage = 0;
        foreach ($items as $item) {
            $currentPercentage += $item->pivot->drop_percentage;

            if ($randomNumber <= $currentPercentage) {
                $selectedItem = $item;
                break;
            }
        }

        if ($selectedItem !== null) {
            CaseOpenedEvent::dispatch($case, $selectedItem);
        }

        return response()->json($selectedItem);
    }

    /**
     * @OA\Post(
     *     path="/api/cases/items/{case}",
     *     summary="Sync case items",
     *     tags={"Cases"},
     *     @OA\Parameter(
     *         name="case",
     *         in="path",
     *         description="Case id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Sync case items object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CasesItemsRequest")
     *     ),
     *     @OA\Response(response="200", description="Sync case items", @OA\JsonContent(
     *         @OA\Property (property="id", type="integer", readOnly="true", example="1"),
     *         @OA\Property (property="name", type="string", example="Case 1"),
     *         @OA\Property (property="type_id", type="integer", example="1"),
     *         @OA\Property (property="price", type="float", example="1.00"),
     *         @OA\Property (property="image", type="string", example="https://via.placeholder.com/150"),
     *         @OA\Property (property="description", type="string", example="Description"),
     *         @OA\Property (property="created_at", type="string", format="date-time", readOnly="true", example="2021-08-04T12:00:00.000000Z"),
     *         @OA\Property (property="updated_at", type="string", format="date-time", readOnly="true", example="2021-08-04T12:00:00.000000Z"),
     *         @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/Item"))
     *     )
     *   )
     * )
     *
     * @param CasesItemsRequest $request
     * @param Cases $case
     * @return JsonResponse
     */
    public function caseItems(CasesItemsRequest $request, Cases $case): JsonResponse
    {
        $items = $request->validated('items');

        $items = $this->addUserIdToRelationTable($items);

        $case->items()->sync($items);

        return response()->json($case->with('items')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/cases/open/exchangeItems",
     *     summary="Exchange opened items",
     *     tags={"Cases"},
     *     @OA\RequestBody(
     *         description="Exchange opened items object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ExchangeOpenedItemsRequest")
     *     ),
     *     @OA\Response(response="200", description="Exchange opened items", @OA\JsonContent(type="object"))
     * )
     *
     * @description This method is used to exchange opened items for balance
     * @param ExchangeOpenedItemsRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function exchangeOpenedItems(ExchangeOpenedItemsRequest $request): JsonResponse
    {
        $user = app()->make('getUserFromDBUsingAuth0');

        $openedCasesIds = collect($request->validated('openedCasesIds'));

        $openedCases = OpenCaseResult::whereIn('id', $openedCasesIds)->get();
        $itemsCost = Item::whereIn('id', $openedCases->pluck('item_id'))->sum('price');

        UpdateUserBalanceEvent::dispatch($user, $itemsCost);
        ReceiveItemFromCaseEvent::dispatch($openedCases->pluck('id')->toArray());

        return response()->json();
    }

    private function syncItems(Cases $case, array $items): void
    {
        $case->items()->sync($this->addUserIdToRelationTable($items));
    }

    private function addUserIdToRelationTable(array|null $items): Collection
    {
        if (is_null($items)) {
            return collect();
        }

        $user_id = app()->make('getUserFromDBUsingAuth0')?->id;

        return collect($items)->map(function ($item) use ($user_id){
            $item['user_id'] = $user_id;
            return $item;
        });
    }
}
