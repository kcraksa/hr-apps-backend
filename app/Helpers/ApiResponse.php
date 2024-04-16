<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'Request successful', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error($message = 'Request failed', $status = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $status);
    }
}
