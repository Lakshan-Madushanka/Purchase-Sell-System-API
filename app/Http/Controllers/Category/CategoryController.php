<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('client.credentials')->only(['index', 'show']);
        $this->middleware('transform.input:' .CategoryTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return $this->showAll($category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->allowedAdminAction();

        $data = $request->only('id', 'name', 'description');

        $rules = [
            'name' => 'required|max:200',
            'description' => 'required|max:1000'
        ];

        $this->validate($request, $rules);

        $category = Category::create($data);

        return $this->showOne($category,'Following category added successfullt', 201);


    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->allowedAdminAction();

        /*
                $rules = [
                    'name' => 'max:200|unique:categories',
                    'description' => 'max:1000'
                ];

                $this->validate($request, $rules);*/

        $category->fill($request->intersect([
            'name',
            'description'
        ]));

        if($category->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }

        $category->save();

        return $this->showOne($category,'Following category updated successfullt', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->allowedAdminAction();

        $category->delete();

        return $this->showOne($category,'Following user deleted successfully', 200);

    }
}
