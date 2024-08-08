<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch divisions with pagination and search
        $data = Team::when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })->where('section_id', $request->section_id)->get();

        return ApiResponse::success($data, "success get data team", 200);
    }

    public function findById($id)
    {
        $exists = Team::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get team", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'section_id' => 'required'
        ]);

        $div = Team::create(["name" => $request->name, "section_id" => $request->section_id]);
        return ApiResponse::success($div, "success create new team", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $div = Team::where("id", $id)->update(["name" => $request->name]);
        return ApiResponse::success($div, "success update team", 200);
    }

    public function delete($id)
    {
        $exists = Team::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete team success", 200);
    }
}
