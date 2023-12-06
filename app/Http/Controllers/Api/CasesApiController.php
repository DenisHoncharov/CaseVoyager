<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CasesRequest;
use App\Http\Resources\CasesResource;
use App\Models\Cases;
use Illuminate\Http\Request;

class CasesApiController extends Controller
{
    public function index(Request $request)
    {
        return CasesResource::collection(Cases::all());
    }

    public function show(Request $request, Cases $case)
    {
        return [
            'id' => $case->id,
            'name' => $case->name,
            'image' => $case->image,
            'price' => $case->price,
            'description' => $case->description,
            'items' => $case->items()
                ->withPivot('drop_percentage')
                ->get()
        ];
    }

    public function create(CasesRequest $request)
    {
        $case = Cases::create($request->validated());

        return response()->json($case);
    }

    public function update(CasesRequest $request, Cases $case)
    {
        $case = $case->update($request->validated());

        return response()->json($case);
    }

    public function delete(Cases $case)
    {
        $case->delete();

        return response()->json();
    }

    public function openCase(Cases $case)
    {
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

        return $selectedItem;
    }
}
