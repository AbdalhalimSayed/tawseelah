<?php

namespace App\Helper;

trait APIResponse
{
//    if Response Is Success
    public function success($data=null, $message="Success", $code=200, $meta=null)  {
        return response()->json(
            [
                "status"        =>true,
                "status_code"   =>$code,
                "message"       =>$message,
                "data"          =>$data,
                "meta"          =>$meta,
            ],
            $code
        );
    }

//    If Response Unsuccess
    public function error($message="Failed", $code=400,$errors=null)  {
        return response()->json(
            [
                "status"        =>false,
                "status_code"   =>$code,
                "message"       =>$message,
                "errors"        =>$errors
            ]
            , $code);
    }

//    If Exists Error in Validation Form
    public function validationError($message="Validation Failed", $errors=null) {
        return $this->error($message, 422, $errors);
    }
}
