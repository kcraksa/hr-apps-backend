<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch departments with pagination and search
        $departments = Department::with(["Division"])->when($search, function($query, $search) {
                        return $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($departments, "success get data department", 200);
    }

    public function findById($id)
    {
        $exists = Department::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get department", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'division_id' => 'required'
        ]);

        $div = Department::create(["name" => $request->name, "division_id" => $request->division_id]);
        return ApiResponse::success($div, "success create new department", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'division_id' => 'required'
        ]);

        $div = Department::where("id", $id)->update(["name" => $request->name,  "division_id" => $request->division_id]);
        return ApiResponse::success($div, "success update department", 200);
    }

    public function delete($id)
    {
        $exists = Department::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete department success", 200);
    }
}
