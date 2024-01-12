<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCasesRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Show all categories",
     *     tags={"Categories"},
     *     @OA\Response(response="200", description="Show all categories", @OA\JsonContent(
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CategoryResource"))
     *     )
     *   )
     * )
     *
     * @param Request $request
     * @return CategoryResource
     *
     */
    public function index(Request $request): JsonResource
    {
        $categories = Category::with('cases')->get();

        return CategoryResource::collection($categories);
    }

    /**
     * @OA\Post(
     *     path="/api/categories/create",
     *     summary="Create category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(response="200", description="Create category", @OA\JsonContent(
     *       ref="#/components/schemas/Category"
     *     )
     *   )
     * )
     *
     * @param CategoryRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function create(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validatedExcept(['cases']));

        $category->cases()->syncWithPivotValues($request->validated('cases'), ['user_id' => app()->make('getUserFromDBUsingAuth0')?->id]);

        return response()->json($category);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     summary="Show category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="Category id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Show category", @OA\JsonContent(
     *       ref="#/components/schemas/Category"
     *     )
     *   )
     * )
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     *
     */
    public function show(Request $request, Category $category): JsonResponse
    {
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'type' => $category->type,
            'image' => $category->image
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     summary="Update category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="Category id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(response="200", description="Update category", @OA\JsonContent(
     *         ref="#/components/schemas/Category"
     *     )
     *   )
     * )
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validatedExcept(['cases']));

        $category->cases()->syncWithPivotValues($request->validated('cases'), ['user_id' => app()->make('getUserFromDBUsingAuth0')?->id]);

        return response()->json($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     summary="Delete category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="Category id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Delete category", @OA\JsonContent(type="object"))
     * )
     *
     * @param Category $category
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json();
    }

    /**
     * @OA\Post(
     *     path="/api/categories/cases/{category}",
     *     summary="Add cases to category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="Category id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="1"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryCasesRequest")
     *     ),
     *     @OA\Response(response="200", description="Add cases to category", @OA\JsonContent(
     *         @OA\Property (property="id", type="integer", example="1"),
     *         @OA\Property (property="name", type="string", example="Category name"),
     *         @OA\Property (property="type_id", type="integer", example="1"),
     *         @OA\Property (property="image", type="string", example="https://i.imgur.com/1.jpg"),
     *         @OA\Property (property="created_at", type="string", format="date-time", example="2021-08-25 12:00:00"),
     *         @OA\Property (property="updated_at", type="string", format="date-time", example="2021-08-25 12:00:00"),
     *         @OA\Property(property="cases", type="array", @OA\Items(ref="#/components/schemas/Cases"))
     *     )
     *   )
     * )
     *
     * @param CategoryCasesRequest $request
     * @param Category $category
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function categoryCases(CategoryCasesRequest $request, Category $category): JsonResponse
    {
        $category->cases()->syncWithPivotValues($request->validated('cases'), ['user_id' => app()->make('getUserFromDBUsingAuth0')?->id]);

        return response()->json($category->with('cases')->get());
    }
}
