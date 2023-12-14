<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCasesRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('cases')->get();

        return CategoryResource::collection($categories);
    }

    public function create(CategoryRequest $request)
    {
        $category = Category::create($request->validatedExcept(['cases']));

        $category->cases()->syncWithPivotValues($request->validated('cases'), ['user_id' => app()->make('getUserFromDBUsingAuth0')?->id]);

        return response()->json($category);
    }

    public function show(Request $request, Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'type' => $category->type,
            'image' => $category->image
        ];
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validatedExcept(['cases']));

        $category->cases()->syncWithPivotValues($request->validated('cases'), ['user_id' => app()->make('getUserFromDBUsingAuth0')?->id]);

        return response()->json($category);
    }

    public function delete(Category $category)
    {
        $category->delete();

        return response()->json();
    }

    public function categoryCases(CategoryCasesRequest $request, Category $category)
    {
        $category->cases()->syncWithPivotValues($request->validated('cases'), ['user_id' => app()->make('getUserFromDBUsingAuth0')?->id]);

        return response()->json($category->with('cases')->get());
    }
}
