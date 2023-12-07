<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('cases')->get();

        return CategoryResource::collection($categories);
    }

    public function show(Request $request, Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'image' => $category->image
        ];
    }

    public function create(CategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = $category->update($request->validated());

        return response()->json($category);
    }

    public function delete(Category $category)
    {
        $category->delete();

        return response()->json();
    }
}
