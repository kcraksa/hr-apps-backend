<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class EmployeeController extends Controller
{
    public function employeeByNip(Request $request, string $nip)
    {
        $data = Employee::with([
            "PlaceOffice",
            "Department",
            "Section",
            "Position",
            "Level",
            "Superior",
            "PersonalData",
            "Address" => function ($query) {
                $query->with('province', 'city', 'district', 'subdistrict');
            }
        ])->where("nip", $nip)->first();
        if (!$data) {
            throw new DataNotFoundException();
        }

        return ApiResponse::success($data, "Get data employee success", 200);
    }
}
