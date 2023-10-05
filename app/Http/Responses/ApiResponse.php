<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data = [], $code = 200, $message = 'Success')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
            'error' => false
        ], $code);
    }

    public static function error($data = [], $code = 500, $message = 'Error')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'code' => $code,
            'error' => true
        ], $code);
    }
}
