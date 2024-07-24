<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendProblem;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AttendProblemController extends Controller
{
    public function index(Request $request)
    {
        // get search query from request
        $search = $request->query('search');
        $user = Auth::user()->id;
        
        // get dynamic values for page and total paginated data
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);

        // get evaluation data joined with users table based on search query
        $attendProblems = AttendProblem::with('user')
            ->where('user_id', $user)
            ->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orWhere('category', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // return list by json response with ApiResponse class
        return ApiResponse::success($attendProblems, "success get attend problem data");
    }

    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:Attend In,Attend Out',
            'type' => 'required|in:Forgot Attend',
            'date' => 'required|date'
        ]);

        // check if data already exists for that user, category and date, if exists return error
        $exist = AttendProblem::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->where('category', $request->category)
            ->first();
        if ($exist) {
            return ApiResponse::error(null, "data already exists for that user and date", 400);
        }

        // create new attend problem data
        $attendProblem = AttendProblem::create($request->all());

        // return created data by json response with ApiResponse class
        return ApiResponse::success($attendProblem, "success create attend problem data");
    }

    public function show($id)
    {
        // find attend problem data by id
        $attendProblem = AttendProblem::find($id);

        // if data not found return error
        if (!$attendProblem) {
            return ApiResponse::error(null, "data not found", 404);
        }

        // return data by json response with ApiResponse class
        return ApiResponse::success($attendProblem, "success get attend problem data");
    }

    public function update(Request $request, $id)
    {
        // find attend problem data by id
        $attendProblem = AttendProblem::find($id);

        // if data not found return error
        if (!$attendProblem) {
            return ApiResponse::error(null, "data not found", 404);
        }

        // validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|in:Attend In,Attend Out',
            'type' => 'required|in:Forgot Attend',
            'date' => 'required|date'
        ]);

        // check if data already exists for that user, category and date, if exists return error
        $exist = AttendProblem::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->where('category', $request->category)
            ->where('id', '!=', $id)
            ->first();
        if ($exist) {
            return ApiResponse::error(null, "data already exists for that user and date", 400);
        }

        // update attend problem data
        $attendProblem->update($request->all());

        // return updated data by json response with ApiResponse class
        return ApiResponse::success($attendProblem, "success update attend problem data");
    }

    public function destroy($id)
    {
        // find attend problem data by id
        $attendProblem = AttendProblem::find($id);

        // if data not found return error
        if (!$attendProblem) {
            return ApiResponse::error(null, "data not found", 404);
        }

        // delete attend problem data
        $attendProblem->delete();

        // return success message by json response with ApiResponse class
        return ApiResponse::success(null, "success delete attend problem data");
    }

    public function approval_list(Request $request)
    {
        // get search query from request
        $search = $request->query('search');
        
        // get dynamic values for page and total paginated data
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        // get evaluation data joined with users table based on search query
        $attendProblems = AttendProblem::with('user')
            ->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orWhere('category', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        // return list by json response with ApiResponse class
        return ApiResponse::success($attendProblems, "success get attend problem data");
    }

    public function approval(Request $request)
    {
        // validate request
        $request->validate([
            'status' => 'required|in:1,2',
            'ids' => 'required|array',
            'ids.*' => 'required|exists:attend_problems,id'
        ]);

        // get ids from request
        $ids = $request->ids;
        $status = $request->status;
        $userId = Auth::user()->id;

        // get attend problems data based on ids
        $attendProblems = AttendProblem::whereIn('id', $ids)->get();

        if ($request->has('isHr')) {
            $data['is_personalia_approved'] = $status;
            $data['personalia_approved_by'] = $userId;
            $data['personalia_approved_date'] = now();
        } else {
            $data['is_supervisor_approved'] = $status;
            $data['supervisor_approved_date'] = now(); 
            $data['supervisor_approved_by'] = $userId;
        }

        // update claim data status to approved
        AttendProblem::whereIn('id', $ids)->update($data);

        // get the updated claim data
        $attendProblems = AttendProblem::whereIn('id', $ids)->get();

        // return success message by json response with ApiResponse class
        return ApiResponse::success($attendProblems, "success bulk approval attend problem data");
    }
}
