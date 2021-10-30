<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($error, $message)
    {
        if ($error == true) {
            return response()->json([
                'error' => false,
                'code' => 200,
                'message' => $message,
            ]);
        }else{
            return response()->json([
                'error' => true,
                'code' => 400,
                'message' => $message,
            ]);
        }
    }

}
