<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch divisions with pagination and search
        $divisions = Division::with(["Directorate", "Department"])->when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->where('directorate_id', $request->query('businessUnitId'))->get();

        return ApiResponse::success($divisions, "success get data division", 200);
    }

    public function findById($id)
    {
        $exists = Division::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get division", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'directorate_id' => 'required'
        ]);

        $div = Division::create(["name" => $request->name, "directorate_id" => $request->directorate_id]);
        return ApiResponse::success($div, "success create new division", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'directorate_id' => 'required'
        ]);

        $div = Division::where("id", $id)->update(["name" => $request->name,  "directorate_id" => $request->directorate_id]);
        return ApiResponse::success($div, "success update division", 200);
    }

    public function delete($id)
    {
        $exists = Division::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete division success", 200);
    }
}
