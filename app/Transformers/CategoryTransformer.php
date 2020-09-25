<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'title' => (string)$category->name,
            'description' => (string)$category->description,
            'creationDate' => (int)$category->created_at,
            'lastchange' => (int)$category->updated_at,
        ];
    }

    public static  function originalAttribute($index) {

        $attributes = [
            'identifier' => 'id',
            'title' =>  'name',
            'description' => 'description',
            'creationDate' => 'created_at',
            'lastchange' => 'updated_at',
        ];

        return isset($attributes[$index]) ?  $attributes[$index] :  null;

    }
}
