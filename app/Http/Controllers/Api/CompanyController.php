<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\ImagesCompany;
use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use App\Models\Increment;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $data = Company::with(["District", "Directorate"])->where("name", "like", "%".$request->search."%")->get();
        return ApiResponse::success($data, "Success get data company", 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|string', // Assuming logo is a base64 string
            'address' => 'required|string|max:255',
            'province_id' => 'required|integer|exists:provinces,id',
            'city_id' => 'required|integer|exists:cities,id',
            'district_id' => 'required|integer|exists:districts,id',
            'subdistrict_id' => 'required|integer|exists:subdistricts,id',
            'rt' => 'required|string|max:3', // Assuming RT is a string with a max length of 3
            'rw' => 'required|string|max:3', // Assuming RW is a string with a max length of 3
        ]);

        $source = "COMPANY";
        $code = "COM";
        $year = date("Y");
        $month = date("m");
        $date = 0;

        $increment = Increment::getOrCreateIncrement($source, $code, $year, $month, $date);

        Company::create([
            "id" => $increment->getFormattedCode(),
            "name" => $request->name,
            "alamat" => $request->address,
            "district_id" => $request->district_id,
            "status" => 1
        ]);

        $fileLogo = GeneralHelper::base64Decode($request->logo);
        $fileNameLogo = 'upload/company/'.$increment->getFormattedCode().'/'.GeneralHelper::generateFilename($request->logo, 'logo');
        $logoPath = Storage::disk('local')->put($fileNameLogo, $fileLogo);

        ImagesCompany::create([
            "company_id" => $increment->getFormattedCode(),
            "logo" => $fileNameLogo,
            "is_default" => 1
        ]);

        return ApiResponse::success(null, "success create new company", 201);
    }

    // get company
    public function show(string $id)
    {
        $data = Company::with(["District", "Directorate"])->where("id", $id)->first();
        // throw error if not found
        if (!$data) {
            return ApiResponse::error("Company not found", 404);
        }
        return ApiResponse::success($data, "Success get data company", 200);
    }

    public function updateStatus(Request $request, string $company)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $company = Company::where("id", $company)-update(["status" => $request->status]);

        return ApiResponse::success(null, "Success update status company", 200);
    }

    public function delete(string $company)
    {
        Company::where("id", $company)->delete();
        ImagesCompany::where("company_id", $company)->delete();

        return ApiResponse::success(null, "Success delete data company", 200);
    }
}
