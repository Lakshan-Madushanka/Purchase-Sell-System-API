<?php
namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

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

    protected function showAll(Collection $collection, $message = 'sucess', $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse($collection, $message, $code);
        }

        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection, $transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successResponse($collection, $message, $code);
    }

    protected function showOne(Model $model, $message = 'successed', $code = 200)
    {
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse($model, $message, $code);
    }

    protected function showMessage($message = 'successed', $code = 200)
    {
        return response()->json($message, $code);
    }

    protected function transformData($data, $transformer)
    {
        // $transformation = Fractal::collection($data)->transformWith(new $transformer);
        $transformation = fractal($data, $transformer);
        return $transformation->toArray();
    }

    protected function sortData(Collection $collection, $transformer)
    {

        if (request()->has('sort_by')) {
            $sortAttribute = $transformer::originalAttribute(request()->get('sort_by'));
            return $collection->sortBy($sortAttribute);
        }

        return $collection;
    }

    protected function filterData(Collection $collection, $transformer)

    {
        $query = request()->query();
        if (!empty($query)) {

            foreach ($query as $attribute => $value) {

                if (isset($attribute, $value)) {

                    /*if($attribute == 'sort_by' or $attribute == 'page') {
                        continue;
                    }*/
                    $filterAttribute = $transformer::originalAttribute($attribute);

                    if ($filterAttribute != null) {
                        $collection = $collection->where($filterAttribute, $value);
                    }
                }
            }
        }
        return $collection;

    }

    protected function paginate(Collection $collection)
    {

        $rules = [
            'per_page' => 'integer|min:1|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 10;
        if (request()->has('per_page')) {
            $perPage = (int)request()->query('per_page');
        }
        $results = $collection->slice(($page - 1) * $perPage, $perPage);

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;

    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 30, function() use($data) {
            return $data;
        });

    }
}

?>
