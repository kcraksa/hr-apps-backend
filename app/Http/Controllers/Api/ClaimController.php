<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    // create function to get list of claims data based on Models/Claim.php and joined with Users table,
    // the list must be paginated and sorted by latest created_at and can filtered by search query
    // return list by json response with ApiResponse class
    public function index(Request $request)
    {
        // get search query from request
        $search = $request->query('search');
        
        // get dynamic values for page and total paginated data
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $user = Auth::user()->id;

        // get list of claims data based on search query
        $claims = Claim::with(['user', 'attachments'])
            ->where('user_id', $user)
            ->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orWhere('description', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // return list by json response with ApiResponse class
        return ApiResponse::success($claims, "success get claims data");
    }

    // create function to get single claim data based on Models/Claim.php
    // return single claim data by json response with ApiResponse class
    public function show($id)
    {
        // get single claim data based on id
        $claim = Claim::with(['user', 'attachments'])->findOrFail($id);

        // if claim data not found, return error response with ApiResponse class
        if (!$claim) {
            return ApiResponse::error(null, "claim data not found", 404);
        }

        // return single claim data by json response with ApiResponse class
        return ApiResponse::success($claim, "success get single claim data");
    }

    // create function to store new claim data based on Models/Claim.php
    // return new claim data by json response with ApiResponse class
    public function store(Request $request)
    {
        // validate request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => 'required|string',
            'category' => 'string|required_if:type,Health Claim,',
            'amount' => 'required|numeric',
            'description' => 'string|required_if:type,Fund Request Claim,Receipt',
        ]);

        // create new claim data based on request data
        $claim = Claim::create($request->all());

        // check if attachment is posted
        if ($request->attachment) {

            // update employee address
            $file = GeneralHelper::base64Decode($request->attachment);
            $filename = 'upload/claim/'.strtolower(str_replace(' ', '-', $request->type)).'/'.$request->user_id.'/'.GeneralHelper::generateFilename($request->attachment, 'attachment');
            $filepath = Storage::disk('local')->put($filename, $file);

            // save the attachment path to the ClaimAttachment table
            $claim->attachments()->create(['attachment' => $filename]);
        }

        // return new claim data by json response with ApiResponse class
        return ApiResponse::success($claim, "success create new claim data");
    }

    // create function to update claim data based on Models/Claim.php
// return updated claim data by json response with ApiResponse class
    public function update(Request $request, $id)
    {
        // validate request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => 'required|string',
            'category' => 'string|required_if:type,Health Claim,',
            'amount' => 'required|numeric',
            'description' => 'string|required_if:type,Fund Request Claim,Receipt',
        ]);

        // get claim data based on id
        $claim = Claim::findOrFail($id);

        // update claim data based on request data
        $claim->update($request->all());

        // check if attachment is posted
        if ($request->attachment) {

            // update employee address
            $file = GeneralHelper::base64Decode($request->attachment);
            $filename = 'upload/claim/'.strtolower(str_replace(' ', '-', $request->type)).'/'.$request->user_id.'/'.GeneralHelper::generateFilename($request->attachment, 'attachment');
            $filepath = Storage::disk('local')->put($filename, $file);

            // save the attachment path to the ClaimAttachment table
            $claim->attachments()->updateOrCreate(['claim_id' => $claim->id], ['attachment' => $filename]);
        }

        // return updated claim data by json response with ApiResponse class
        return ApiResponse::success($claim, "success update claim data");
    }

    // create function to delete claim data based on Models/Claim.php
    // return deleted claim data by json response with ApiResponse class
    public function delete($id)
    {
        // get claim data based on id
        $claim = Claim::findOrFail($id);

        // delete claim data based on id
        $claim->delete();

        // return deleted claim data by json response with ApiResponse class
        return ApiResponse::success($claim, "success delete claim data");
    }

    // create function to get list of claims data based on Models/Claim.php and joined with Users table,
    // the list must be paginated and sorted by latest created_at and can filtered by search query
    // return list by json response with ApiResponse class
    public function indexAs(Request $request)
    {
        // get search query from request
        $search = $request->query('search');
        $user_id = Auth::user()->id;
        
        // get dynamic values for page and total paginated data
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);

        // get list of claims data based on search query
        $claims = Claim::with(['user', 'attachments'])
                    ->join('users', 'claims.user_id', '=', 'users.id')
                    ->join('relations', 'users.id', '=', 'relations.employee_id')
                    ->where('relations.lead_id', $user_id)
                    ->where(function($query) use ($search) {
                        $query->whereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%$search%");
                        })
                        ->orWhere('description', 'like', "%$search%");
                    })
                    ->orderBy('claims.created_at', 'desc')
                    ->paginate($perPage, ['*'], 'page', $page);

        // return list by json response with ApiResponse class
        return ApiResponse::success($claims, "success get claims data");
    }

    // create function to get list of claims data based on Models/Claim.php and joined with Users table,
    // the list must be paginated and sorted by latest created_at and can filtered by search query
    // return list by json response with ApiResponse class
    public function approval_list(Request $request)
    {
        // get search query from request
        $search = $request->query('search');
        
        // get dynamic values for page and total paginated data
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        // get list of claims data based on search query
        $claims = Claim::with(['user', 'attachments'])
            ->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orWhere('description', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        // return list by json response with ApiResponse class
        return ApiResponse::success($claims, "success get claims data");
    }

    // create function to approve claim data based on Models/Claim.php
    // return approved claim data by json response with ApiResponse class
    public function approval(Request $request)
    {
        // validate request data
        $request->validate([
            'status' => 'required|in:1,2',
            'ids' => 'required|array',
            'ids.*' => 'required|exists:claims,id'
        ]);
        $ids = $request->ids;

        $data = [];
        $status = $request->status;
        $userId = Auth::user()->id;

        if ($request->has('isHr')) {
            $data['is_approve_personalia'] = $status;
            $data['approve_personalia_by'] = $userId;
            $data['approve_personalia_at'] = now();
        } elseif ($request->has('isFa')) {
            $data['is_approve_fa'] = $status;
            $data['approve_fa_by'] = $userId;
            $data['approve_fa_at'] = now();
        } else {
            $data['is_approve_supervisor'] = $status;
            $data['approve_supervisor_by'] = $userId;
            $data['approve_supervisor_at'] = now();
        }

        // update claim data status to approved
        Claim::whereIn('id', $ids)->update($data);

        // get the updated claim data
        $claim = Claim::whereIn('id', $ids)->get();


        // return approved claim data by json response with ApiResponse class
        return ApiResponse::success($claim, "success approve claim data");
    }
}
