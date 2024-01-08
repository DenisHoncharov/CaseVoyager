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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return TypeResource::collection(Type::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(TypeRequest $request)
    {
        $type = Type::create($request->validated());

        return response()->json($type);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Type $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeRequest $request, Type $type)
    {
        $type = $type->update($request->validated());

        return response()->json($type);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Type $type)
    {
        $type->delete();

        return response()->json();
    }
}
