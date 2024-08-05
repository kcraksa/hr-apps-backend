<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Directorate;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class DirectorateController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch divisions with pagination and search
        $data = Directorate::with(["Company"])->where("company_id", $request->query('company_id'))->get();

        return ApiResponse::success($data, "success get data business unit", 200);
    }

    public function findById($id)
    {
        $exists = Directorate::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->first();
        return ApiResponse::success($exists, "success get business unit", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'company_id' => 'required'
        ]);

        $div = Directorate::create(["name" => $request->name, "company_id" => $request->company_id]);
        return ApiResponse::success($div, "success create new business unit", 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'company_id' => 'required'
        ]);

        $div = Directorate::where("id", $id)->update(["name" => $request->name, "company_id" => $request->company_id]);
        return ApiResponse::success($div, "success update business unit", 200);
    }

    public function delete($id)
    {
        $exists = Directorate::find($id);
        if (!$exists) {
            throw new DataNotFoundException();
        }

        $exists->delete();
        return ApiResponse::success(null, "delete business unit success", 200);
    }
}
