<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemsResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    public function index(Request $request)
    {
        return ItemsResource::collection(Item::all());
    }

    public function show(Request $request, Item $item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'image' => $item->image
        ];
    }

    public function create(ItemRequest $request)
    {
        $item = Item::create($request->validated());

        return response()->json($item);
    }

    public function update(ItemRequest $request, Item $item)
    {
        $item = $item->update($request->validated());

        return response()->json($item);
    }

    public function delete(Item $item)
    {
        $item->delete();

        return response()->json();
    }
}
