<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relation;
use App\Helpers\ApiResponse;

class RelationController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch positions with pagination and search
        $positions = Relation::with(["user"])->when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($positions, "success get data relation", 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'lead_id' => 'required'
        ]);

        $div = Relation::create(["lead_id" => $request->lead_id]);
        return ApiResponse::success($div, "success create new relation", 201);
    }
}
