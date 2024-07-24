<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\UserLeaveData;
use App\Models\PermissionDate;
use App\Models\PermissionDocument;
use App\Models\PermissionSick;
use App\Models\Relation;
use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth()->user()->id;
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        // Fetch permissions with pagination and search
        $permission = Permission::with(["PermissionDate", "PermissionDocument"])
                        ->where("permissions.user_id", "=", $user_id)
                        ->orderBy("permissions.id", "DESC")
                        ->paginate($limit, ['*'], 'page', $page);

        return ApiResponse::success($permission, "success get data permission", 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            "user_id" => "required|exists:users,id",
            "type" => "required|in:Absent,Permission,Sick Leave,Specific Paid Leave,Overtime",
            "category" => "required|in:Leave,Leave of Absent,Work From Home,Late Arrival,Permission to Leave,Business Trip,Sick Leave,Employee Marriage,Wife Giving Birth,Employee Giving Birth,Meal Allowance,Overtime",
            "date" => "required",
            "reason" => "required_if:type,Absent|required_if:type,Permission",
            "time" => "required_if:type,Permission",
            "time_from" => "required_if:type,Overtime",
            "time_to" => "required_if:type,Overtime",
            "subcategory" => "required_if:type,Overtime|in:Weekday,Weekend",
            "proof" => "required_if:type,Sick Leave,Specific Paid Leave",
            "diagnosis" => "required_if:type,Sick Leave",
            "hospital_name" => "required_if:type,Sick Leave"
        ]);

        $time = "00:00";
        if ($request->time) {
            $time = $request->time;
        }

        // check if permission already exists
        $check = Permission::where('type', $request->type)
                    ->where('category', $request->category)
                    ->where('user_id', $request->user_id)
                    ->where('status', 0)
                    ->whereHas('permissionDate', function ($query) use ($request) {
                        $query->where('fromdatetime', $request->date)
                            ->where('todatetime', $request->date);
                    })
                    ->with(['permissionDate' => function ($query) use ($request) {
                        $query->where('fromdatetime', $request->date)
                            ->where('todatetime', $request->date);
                    }])
                    ->exists();
        if ($check) {
            return ApiResponse::error("You have permission request on the date", 422);
        }

        // input request
        $permission = Permission::create([
            "user_id" => $request->user_id,
            "type" => $request->type,
            "category" => $request->category,
            "reason" => $request->reason,
            "status" => 0
        ]);

        if ($request->type === "Overtime") {
            $permission->subcategory = $request->subcategory;
            $permission->save();
        }

        // input date
        $permissionDate = PermissionDate::create([
            "permission_id" => $permission->id,
            "fromdatetime" => $request->date." ".$time,
            "todatetime" => $request->date." ".$time,
            "datediff" => 0
        ]);

        if ($request->type === "Overtime") {
            $permissionDate->fromdatetime = $request->date." ".$request->time_from;
            $permissionDate->todatetime = $request->date." ".$request->time_to;
            $permissionDate->save();
        }

        if ($request->type == "Sick Leave") {
            // update employee address
            $file = GeneralHelper::base64Decode($request->proof);
            $filename = 'upload/permission/sick-leave/'.$request->user_id.'/'.GeneralHelper::generateFilename($request->proof, 'proof');
            $filepath = Storage::disk('local')->put($filename, $file);

            PermissionDocument::create([
                "permission_id" => $permission->id,
                "type_file" => "Sick Leave Document",
                "file" => $filename,
                "description" => "Sick Leave Document"
            ]);

            PermissionSick::create([
                "permission_id" => $permission->id,
                "diagnosis" => $request->diagnosis,
                "hospital_name" => $request->hospital_name
            ]);
        }

        if ($request->type == "Specific Paid Leave") {
            // update employee address
            $file = GeneralHelper::base64Decode($request->proof);
            $filename = 'upload/permission/specific-paid-leave/'.$request->user_id.'/'.GeneralHelper::generateFilename($request->proof, 'proof');
            $filepath = Storage::disk('local')->put($filename, $file);

            PermissionDocument::create([
                "permission_id" => $permission->id,
                "type_file" => "Specific Paid Leave Document",
                "file" => $filename,
                "description" => "Specific Paid Leave Document"
            ]);
        }

        return ApiResponse::success(null, "success create permission", 201);
    }

    public function indexPermissionAs(Request $request)
    {
        // $user_id = Auth()->user()->id;
        $relations = Relation::select("employee_id")->where("lead_id", Auth()->user()->id)->get()->pluck('employee_id');
        $page = $request->query('page', 1);

        // Fetch divisions with pagination and search
        $permission = Permission::with("PermissionDate")->whereIn("user_id", $relations)
                        ->orderBy("id", "DESC")
                        ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($permission, "success get data permission", 200);
    }

    public function approval_list(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        // Fetch divisions with pagination and search
        $permission = Permission::with(["PermissionDate", "User", "PermissionDocument", "SupervisorApproval", "PersonaliaApproval", "FaApproval"])
            ->when($request->search, function ($query) use ($request) {
            $query->whereHas('User', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->search . '%');
            });
            })
            ->orderBy("id", "DESC")
            ->paginate($limit, ['*'], 'page', $page);

        return ApiResponse::success($permission, "success get data permission", 200);
    }

    public function approval(Request $request)
    {
        $request->validate([
            "status" => "required|in:1,2",
            "ids" => "required|array",
            "ids.*" => "required|exists:permissions,id"
        ]);

        $permissionIds = $request->ids;
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        foreach ($permissions as $permission) {
            $permission->supervisor_approval = $request->status;
            $permission->supervisor_approval_date = date("Y-m-d H:i:s");
            $permission->supervisor_approval_by = Auth()->user()->id;
            $permission->save();
        }

        return ApiResponse::success(null, "success bulk approval permission", 200);
    }
}
