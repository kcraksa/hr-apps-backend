<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch positions with pagination and search
        $positions = Position::with(["Team"])->when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($positions, "success get data position", 200);
    }

    public function findById($id)
    {
        $exists = Position::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get position", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'team_id' => 'required'
        ]);

        $div = Position::create(["name" => $request->name, "team_id" => $request->team_id]);
        return ApiResponse::success($div, "success create new position", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'team_id' => 'required'
        ]);

        $div = Position::where("id", $id)->update(["name" => $request->name,  "team_id" => $request->team_id]);
        return ApiResponse::success($div, "success update position", 200);
    }

    public function delete($id)
    {
        $exists = Position::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete position success", 200);
    }
}
