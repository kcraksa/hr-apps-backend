<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Helpers\ApiResponse;

class EvaluationController extends Controller
{
    // make crud api based on Evaluation model
    public function index(Request $request)
    {
        // get search query from request
        $search = $request->query('search');
        
        // get dynamic values for page and total paginated data
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);

        // get evaluation data joined with users table based on search query
        $evaluations = Evaluation::with('user')
            ->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orWhere('evaluation_type', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        // return list by json response with ApiResponse class
        return ApiResponse::success($evaluations, "success get evaluation data");
    }

    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'evaluations' => 'required|array',
        ]);

        // check if evaluation already exists and throw as error if exists
        $checkEvaluation = Evaluation::where('user_id', $request->user_id)->first();
        if ($checkEvaluation) {
            return ApiResponse::error("evaluation data already exists", 400);
        }

        // create new evaluation data with loop for each evaluations
        $evaluations = [];
        foreach ($request->evaluations as $key => $evaluation) {
            $evaluations[] = Evaluation::create([
                'user_id' => $request->user_id,
                'evaluation_type' => $key,
                'value' => $evaluation,
            ]);
        }

        // return created data by json response with ApiResponse class
        return ApiResponse::success($evaluations, "success create evaluation data");
    }

    public function show($id)
    {
        return Evaluation::find($id);
    }

    public function update(Request $request, $id)
    {
        $evaluation = Evaluation::find($id);
        $evaluation->update($request->all());
        return $evaluation;
    }

    public function destroy($id)
    {
        return Evaluation::destroy($id);
    }
}
