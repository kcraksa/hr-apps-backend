<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch sections with pagination and search
        $sections = Section::with(["Department", "Team"])->when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($sections, "success get data section", 200);
    }

    public function findById($id)
    {
        $exists = Section::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get section", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required'
        ]);

        $div = Section::create(["name" => $request->name, "department_id" => $request->department_id]);
        return ApiResponse::success($div, "success create new section", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required'
        ]);

        $div = Section::where("id", $id)->update(["name" => $request->name,  "department_id" => $request->department_id]);
        return ApiResponse::success($div, "success update section", 200);
    }

    public function delete($id)
    {
        $exists = Section::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete section success", 200);
    }
}
