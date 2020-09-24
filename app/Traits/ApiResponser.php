<?php
namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{

    protected function successResponse($data, $message, $code = 200)
    {
        return response()->json(['message' => $message, 'data' => $data], $code);
    }

    protected function errorResponse($message, $code = 404)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $message='sucess', $code = 200)
    {
       return  $this->successResponse($collection, $message , $code);
        //return response()->json(['data' => $collection], $code);
    }

    protected function showOne(Model $model, $message='successed', $code = 200)
    {
        return  $this->successResponse($model, $message , $code);
    }

    protected function showMessage($message='successed', $code = 200)
    {
        return  response()->json($message, $code);
    }

}

?>
