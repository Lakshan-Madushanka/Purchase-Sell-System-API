<?php
namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Fractal\Facades\Fractal;

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
        if($collection->isEmpty()) {
            return  $this->successResponse($collection, $message , $code);
        }

        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);
        return  $this->successResponse($collection, $message , $code);
    }

    protected function showOne(Model $model, $message='successed', $code = 200)
    {
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return  $this->successResponse($model, $message , $code);
    }

    protected function showMessage($message='successed', $code = 200)
    {
        return  response()->json($message, $code);
    }

    protected function transformData($data, $transformer)
    {
       // $transformation = Fractal::collection($data)->transformWith(new $transformer);
        $transformation =  fractal($data, $transformer);
        return $transformation->toArray();
    }

}

?>
