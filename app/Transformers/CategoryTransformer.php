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
            'creationDate' => $category->created_at,
            'lastchange' => $category->updated_at,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id)
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $category->id)
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', $category->id)
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $category->id)
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $category->id)
                ]
            ]
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

    public static  function transformAttribute($index) {

        $attributes = [
            'id' => 'identifier',
            'name' =>  'title',
            'description' => 'description',
            'created_at' => 'creationDate',
            'updated_at' => 'lastchange',
        ];

        return isset($attributes[$index]) ?  $attributes[$index] :  null;

    }
}
