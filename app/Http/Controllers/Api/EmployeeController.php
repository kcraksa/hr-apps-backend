<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeAddress;
use App\Models\EmployeePersonalData;
use App\Models\EmployeeFamily;
use App\Models\EmployeeContact;
use App\Models\EmployeeBank;
use App\Models\EmployeeDriverLicense;
use App\Models\EmployeeDocument;
use App\Models\EmployeeVerklaring;
use App\Models\UserLeaveData;
use App\Models\UserHealthBalance;
use App\Models\Relation;
use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use App\Exceptions\DataNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        // Fetch divisions with pagination and search
        $data = Employee::with([
            "User",
            "Position",
            "Level",
            "Position.Team",
            "Position.Team.Section",
            "Position.Team.Section.Department",
            "Position.Team.Section.Department.Division",
        ])->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($data, "success get data employee", 200);
    }

    public function dropdown(Request $request)
    {
        $search = $request->query('search', '');
        // Fetch divisions with pagination and search
        $data = Employee::with([
            "User"
        ])
        ->when($search, function($query, $search) {
            return $query->whereHas('User', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        })
        ->paginate(10, ['*'], 'page', 1);

        return ApiResponse::success($data, "success get data employee", 200);
    }

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

    public function employeeByID(Request $request, string $id)
    {
        $data = User::with([
            "Employee",
            "Employee.Position",
            "Employee.Position.Team",
            "Employee.Position.Team.Section",
            "Employee.Position.Team.Section.Department",
            "Employee.Level",
        ])->where("id", $id)->first();
        if (!$data) {
            throw new DataNotFoundException();
        }

        return ApiResponse::success($data, "Get data employee success", 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => "required",
            "employment_status" => "required",
            "phone" => "required",
            "email" => "email|required",
            "position_id" => "required",
            "level_id" => "required",
            "superior_1" => "required",
            "join_date" => "required",
            "contract_start_date" => "required",
            "contract_end_date" => "required",
            "fixed_date" => "required",
        ]);

        // insert ke table user
        $defaultPassword = "12345678";
        $prefixNIP = "HR23";

        $user = User::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "password" => Hash::make($defaultPassword),
            "nip" => $prefixNIP.rand(1000, 9999)
        ]);

        Employee::create([
            "user_id" => $user->id,
            "join_date" => $request->join_date,
            "contract_start_date" => $request->contract_start_date,
            "contract_end_date" => $request->contract_end_date,
            "fixed_date" => $request->fixed_date,
            "position_id" => $request->position_id,
            "level_id" => $request->level_id,
            "employment_status" => $request->employment_status,
            // "superior_id" => $request->superior_1
        ]);

        // create leave and health balance default
        UserLeaveData::create([
            "user_id" => $user->id,
            "year" => date("Y"),
            "leave_balance" => 0,
            "expired_balance" => 0,
            "total_balance" => 0,
            "status" => 1
        ]);

        UserHealthBalance::create([
            "user_id" => $user->id,
            "year" => date("Y"),
            "health_balance" => 0,
            "total_balance" => 0,
            "balance_update" => date("Y-m-d"),
            "status" => 1
        ]);

        return ApiResponse::success(null, "create new employee success", 201);
    }

    public function update(Request $request, string $nip)
    {
        try {
            $validated = $request->validate([
                'fullname' => 'required',
                'nip' => 'required',
                'phone_number' => 'required',
                'self_photo' => 'required',
                'office_place' => 'required',
                'department' => 'required',
                'section' => 'required',
                'position' => 'required',
                'level' => 'required',
                'superior' => 'required',
                'id_card' => 'required',
                'identity_number' => 'required',
                'address' => 'required',
                'province_id' => 'required',
                'city_id' => 'required',
                'district_id' => 'required',
                'subdistrict_id' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'is_according_ktp' => 'required',
                'gender' => 'required',
                'weight' => 'required',
                'height' => 'required',
                'blood_type' => 'required',
                'placeofbirth' => 'required',
                'dateofbirth' => 'required',
                'religion' => 'required',
                'nationality' => 'required',
                'biological_father_name' => 'required',
                'biological_father_dob' => 'required',
                'biological_father_pob' => 'required',
                'biological_father_address' => 'required',
                'biological_mother_name' => 'required',
                'biological_mother_dob' => 'required',
                'biological_mother_pob' => 'required',
                'biological_mother_address' => 'required',
                'spouse_children' => 'required',
                'contact_personal_email' => 'required',
                'contact_emergency_number' => 'required',
                'contact_relationship' => 'required',
                'contact_name_of_person' => 'required',
                'bank' => 'required',
                'bank_account' => 'required',
                'driver_license_photos.*' => 'required',
                'driver_license_type.*' => 'required',
                'driver_license_number.*' => 'required',
                'family_card_photo' => 'required',
                'family_card_number' => 'required',
                'npwp_photo' => 'required',
                'tax_name' => 'required',
                'tax_number' => 'required',
                'bpjs_kesehatan_photo' => 'required',
                'bpjs_ketenagakerjaan_photo' => 'required',
                'bpjs_kesehatan_number' => 'required',
                'bpjs_ketenagakerjaan_number' => 'required',
                'education_certificate_photo' => 'required',
                'transcript_photo' => 'required',
                'last_education' => 'required',
                'major' => 'required',
                'institution' => 'required',
                'verklaring_photos.*' => 'required'
            ]);


            // update employees main data
            Employee::where("nip", $nip)->update([
                "fullname" => $request->fullname,
                "nip" => $request->nip,
                "phone_number" => $request->phone_number,
                "office_place_id" => $request->office_place,
                "department_id" => $request->department,
                "section_id" => $request->section,
                "position_id" => $request->position,
                "level_id" => $request->level,
                "superior_nip" => $request->superior_nip,
            ]);


            // update employee address
            $fileIdCard = GeneralHelper::base64Decode($request->id_card);
            $fileNameIdCard = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->id_card, 'id_card');
            $idCardPath = Storage::disk('local')->put($fileNameIdCard, $fileIdCard);

            
            EmployeeAddress::updateOrCreate(
                ["nip" => $nip],
                [
                    "nip" => $nip,
                    "id_card" => $fileNameIdCard,
                    "identity_number" => $request->identity_number,
                    "address" => $request->address,
                    "province_id" => $request->province_id,
                    "city_id" => $request->city_id,
                    "district_id" => $request->district_id,
                    "subdistrict_id" => $request->subdistrict_id,
                    "rt" => $request->rt,
                    "rw" => $request->rw,
                    "is_according_ktp" => $request->is_according_ktp,
                ]
            );

            // update personal data
            $fileSelfPhoto = GeneralHelper::base64Decode($request->self_photo);
            $fileNameSelfPhoto = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->self_photo, 'self_photo');
            $idCardPath = Storage::disk('local')->put($fileNameSelfPhoto, $fileSelfPhoto);

            EmployeePersonalData::updateOrCreate(
                ["nip" => $nip],
                [
                    "nip" => $nip,
                    "gender" => $request->gender,
                    "weight" => $request->weight,
                    "height" => $request->height,
                    "bloodtype" => $request->blood_type,
                    "placeofbirth" => $request->placeofbirth,
                    "dateofbirth" => $request->dateofbirth,
                    "religion" => $request->religion,
                    "nationality" => $request->nationality,
                    "self_photo" => $fileNameSelfPhoto
                ]
            );

            // update employee family
            EmployeeFamily::updateOrCreate(
                ["nip" => $nip],
                [
                    "nip" => $nip,
                    "biological_father_name" => $request->biological_father_name,
                    "biological_father_dob" => $request->biological_father_dob,
                    "biological_father_pob" => $request->biological_father_pob,
                    "biological_father_address" => $request->biological_father_address,
                    "biological_mother_name" => $request->biological_mother_name,
                    "biological_mother_name" => $request->biological_mother_name,
                    "biological_mother_dob" => $request->biological_mother_dob,
                    "biological_mother_pob" => $request->biological_mother_pob,
                    "biological_mother_address" => $request->biological_mother_address,
                    "spouse_children" => $request->spouse_children
                ]
            );

            // update employee contact
            EmployeeContact::updateOrCreate(
                ["nip" => $nip],
                [
                    "nip" => $nip,
                    "contact_personal_email" => $request->contact_personal_email,
                    "contact_emergency_number" => $request->contact_emergency_number,
                    "contact_relationship" => $request->contact_relationship,
                    "contact_name_of_person" => $request->contact_name_of_person,
                ]
            );

            // update employee bank
            EmployeeBank::updateOrCreate(
                ["nip" => $nip],
                [
                    "nip" => $nip,
                    "bank" => $request->bank,
                    "bank_account" => $request->bank_account,
                ]
            );

            foreach ($request->driver_license_type as $key => $value) {
                // update employee driver licenses

                $fileDrivingLicense = GeneralHelper::base64Decode($request->driver_license_photos[$key]);
                $fileNameDrivingLicense = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->driver_license_photos[$key], 'driving_license_'.$value);
                $idCardPath = Storage::disk('local')->put($fileNameDrivingLicense, $fileDrivingLicense);

                EmployeeDriverLicense::updateOrCreate(
                    [
                        "nip" => $nip,
                        "driver_license_type" => $value
                    ],
                    [
                        "nip" => $nip,
                        "driver_license_type" => $value,
                        "driver_license_number" => $request->driver_license_number[$key],
                        "driver_license_photo" => $fileNameDrivingLicense
                    ]
                );
            }

            // update employee documents
            $fileFamilyCard = GeneralHelper::base64Decode($request->family_card_photo);
            $fileNameFamilyCard = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->family_card_photo, 'family_card');
            $idCardPath = Storage::disk('local')->put($fileNameFamilyCard, $fileFamilyCard);

            $fileNPWP = GeneralHelper::base64Decode($request->npwp_photo);
            $fileNameNPWP = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->npwp_photo, 'npwp');
            $idCardPath = Storage::disk('local')->put($fileNameNPWP, $fileNPWP);

            $fileBPJSKesehatan = GeneralHelper::base64Decode($request->bpjs_kesehatan_photo);
            $fileNameBPJSKesehatan = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->bpjs_kesehatan_photo, 'BPJSKesehatan');
            $idCardPath = Storage::disk('local')->put($fileNameBPJSKesehatan, $fileBPJSKesehatan);

            $fileBPJSKetenagakerjaan = GeneralHelper::base64Decode($request->bpjs_ketenagakerjaan_photo);
            $fileNameBPJSKetenagakerjaan = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->bpjs_ketenagakerjaan_photo, 'BPJSKetenagakerjaan');
            $idCardPath = Storage::disk('local')->put($fileNameBPJSKetenagakerjaan, $fileBPJSKetenagakerjaan);

            $fileEducationCertificate = GeneralHelper::base64Decode($request->education_certificate_photo);
            $fileNameEducationCertificate = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->education_certificate_photo, 'EducationCertificate');
            $idCardPath = Storage::disk('local')->put($fileNameEducationCertificate, $fileEducationCertificate);

            $fileEducationCertificate = GeneralHelper::base64Decode($request->education_certificate_photo);
            $fileNameEducationCertificate = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->education_certificate_photo, 'EducationCertificate');
            $idCardPath = Storage::disk('local')->put($fileNameEducationCertificate, $fileEducationCertificate);

            $fileTranscript = GeneralHelper::base64Decode($request->transcript_photo);
            $fileNameTranscript = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($request->transcript_photo, 'Transcript');
            $idCardPath = Storage::disk('local')->put($fileNameTranscript, $fileTranscript);

            EmployeeDocument::updateOrCreate(
                ["nip" => $nip],
                [
                    "nip" => $nip,
                    "family_card_number" => $request->family_card_number,
                    "family_card_photo" => $fileNameFamilyCard,
                    "tax_name" => $request->tax_name,
                    "tax_number" => $request->tax_number,
                    "npwp_photo" => $fileNameNPWP,
                    "bpjs_kesehatan_number" => $request->bpjs_kesehatan_number,
                    "bpjs_kesehatan_photo" => $fileNameBPJSKesehatan,
                    "bpjs_ketenagakerjaan_number" => $request->bpjs_ketenagakerjaan_number,
                    "bpjs_ketenagakerjaan_photo" => $fileNameBPJSKetenagakerjaan,
                    "education_certificate_photo" => $fileNameEducationCertificate,
                    "transcript_photo" => $fileNameTranscript,
                    "last_education" => $request->last_education,
                    "major" => $request->major,
                    "institution" => $request->institution,
                ]
            );

            foreach ($request->verklaring_photos as $key => $value) {
                // update employee verklaring

                $fileVerklaring = GeneralHelper::base64Decode($value);
                $fileNameVerklaring = 'upload/documents/'.$nip.'/'.GeneralHelper::generateFilename($value, 'Verklaring');
                $idCardPath = Storage::disk('local')->put($fileNameVerklaring, $fileVerklaring);

                EmployeeVerklaring::create(
                    [
                        "nip" => $nip,
                        "verklaring_photo" => $fileNameVerklaring
                    ],
                );
            }

            return ApiResponse::success([], "Update data success", 200);
        } catch (Exception $e) {
            return ApiResponse::error("Internal Server Error");
        }
    }

    public function getUsersByLeadId(Request $request)
    {
        $leadId = Auth::user()->id;
        $search = $request->query('search', '');

        // Fetch relations where lead_id is 1
        $relations = Relation::with(['User'])->where('lead_id', $leadId)->get();

        // Extract user IDs from relations
        $userIds = $relations->pluck('employee_id')->toArray();

        // Fetch users based on extracted user IDs and filter by name or nip
        $users = User::whereIn('id', $userIds)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('nip', 'LIKE', "%{$search}%");
                });
            })
            ->get();

        return ApiResponse::success($users, "success get data employee", 200);
    }  
}
