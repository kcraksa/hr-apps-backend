<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\FunctionModel;
use App\Models\Role;
use App\Models\Employee;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function getModule(Request $request)
    {
        $data = Module::all();

        return ApiResponse::success($data, "Get data module success", 200);
    }

    public function getFunction(Request $request, string $module_id)
    {
        $data = FunctionModel::where("id_module", $module_id)->get();

        return ApiResponse::success($data, "Get data function success", 200);
    }

    public function searchEmployee(Request $request)
    {
        $data = Employee::with(['user'])->where("fullname", $request->search)->orWhere("nip", $request->search)->first();
        $roleAssigned = Role::with("FunctionModule")->where("user_id", $data->user->id)->get();

        $functionNames = [];
        foreach ($roleAssigned as $item) {
            $functionNames[] = $item->FunctionModule->name;
        }

        $data->role_assigned = count($roleAssigned);
        $data->function = $functionNames;
        $data->roles = $this->getUserRoles($data->user->id);

        return ApiResponse::success($data, "Get data employee success", 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'function_id' => 'required',
            'scope_id' => 'required',
            'create' => 'required',
            'read' => 'required',
            'update' => 'required',
            'delete' => 'required',
        ]);

        Role::updateOrCreate(
            [
                "user_id" => $request->user_id,
                "function_id" => $request->function_id,
                "scope_id" => $request->scope_id
            ],
            [
                "user_id" => $request->user_id,
                "function_id" => $request->function_id,
                "scope_id" => $request->scope_id,
                "create" => $request->create,
                "read" => $request->read,
                "update" => $request->update,
                "delete" => $request->delete
            ]
        );

        return ApiResponse::success(null, "Role has been updated");
    }

    public function getUserRoles($user_id)
    {
        return Module::whereHas('functions', function ($query) use ($user_id) {
            $query->whereHas('role', function ($roleQuery) use ($user_id) {
                $roleQuery->where('user_id', $user_id);
            });
        })->with(['functions' => function ($query) use ($user_id) {
            $query->whereHas('role', function ($roleQuery) use ($user_id) {
                $roleQuery->where('user_id', $user_id);
            })->with(['role' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->with(['scope']);
            }]);
        }])->get();
    }
}
