<?php


namespace App\Traits\Api;


trait ApiResponseTrait
{

    public function returnSuccessfulResponse($msg="",$code=200){
        return response()->json([
            'status' => true,
            'msg'    => $msg,
            'code'   => $code
        ],$code);
    }


    public function returnDataResponse($data,$key="data",$msg="",$code=200){
        return response()->json([
            'status' => true,
            'msg'    => $msg,
            'code'   => $code,
            $key   => $data,
        ],$code);
    }

    public function returnErrorResponse($code=500,$msg=""){
        return response()->json([
            'status' => false,
            'msg'    => $msg,
            'code'   => $code
        ],$code);
    }

    public function returnValidationErrorResponse($validator,$code=403)
    {
        return $this->returnErrorResponse($code, $validator->errors());
    }

}
