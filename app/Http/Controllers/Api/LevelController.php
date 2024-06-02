<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch levels with pagination and search
        $levels = Level::when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($levels, "success get data level", 200);
    }

    public function findById($id)
    {
        $exists = Level::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get level", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'levelA' => 'nullable|string|max:255',
            'levelN' => 'nullable|string|max:255',
            'levelEm' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'specialist' => 'nullable|string|max:255',
            'executive' => 'nullable|string|max:255',
            'health_balance' => 'required|string|min:0',
            'meal_allowance' => 'required|string|min:0',
            'transportation_fee' => 'required|string|min:0',
        ]);

        $mappedData = $request->only([
            'levelA',
            'levelN',
            'levelEm',
            'group',
            'specialist',
            'executive',
            'health_balance',
            'meal_allowance',
            'transportation_fee'
        ]);

        $div = Level::create([...$mappedData, "status" => 1]);
        return ApiResponse::success($div, "success create new level", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'levelA' => 'nullable|string|max:255',
            'levelN' => 'nullable|string|max:255',
            'levelEm' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'specialist' => 'nullable|string|max:255',
            'executive' => 'nullable|string|max:255',
            'health_balance' => 'required|min:0',
            'meal_allowance' => 'required|min:0',
            'transportation_fee' => 'required|min:0',
        ]);

        $mappedData = $request->only([
            'levelA',
            'levelN',
            'levelEm',
            'group',
            'specialist',
            'executive',
            'health_balance',
            'meal_allowance',
            'transportation_fee'
        ]);

        $div = Level::where("id", $id)->update($mappedData);
        return ApiResponse::success($div, "success update level", 200);
    }

    public function delete($id)
    {
        $exists = Level::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete level success", 200);
    }
}
