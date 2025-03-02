<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function ResponseSuccess($data = null, $meta = null, $message = 'Action performed', $code = JsonResponse::HTTP_OK){
        return response()->json([
            'status'=>$code,
            'data'=>$data,
            'message'=>$message,
            'meta'=>$meta
        ], $code);
    }

    public function ResponseError($code = JsonResponse::HTTP_BAD_REQUEST, $message='Can not perform action'){
        return response()->json([
            'status'=>$code,
            'message'=>$message
        ], $code);
    }
}
