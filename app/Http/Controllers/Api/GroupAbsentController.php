<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GroupAbsent;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class GroupAbsentController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch groupabsents with pagination and search
        $groupabsents = GroupAbsent::when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($groupabsents, "success get data groupabsent", 200);
    }

    public function findById($id)
    {
        $exists = GroupAbsent::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get groupabsent", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $mappedData = $request->only([
            'name'
        ]);

        $div = GroupAbsent::create($mappedData);
        return ApiResponse::success($div, "success create new groupabsent", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $mappedData = $request->only([
            'name'
        ]);

        $div = GroupAbsent::where("id", $id)->update($mappedData);
        return ApiResponse::success($div, "success update groupabsent", 200);
    }

    public function delete($id)
    {
        $exists = GroupAbsent::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete groupabsent success", 200);
    }
}
