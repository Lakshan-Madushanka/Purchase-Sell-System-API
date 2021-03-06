<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Product;
use App\Transformers\ProductTransformer;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:' .ProductTransformer::class)->only(['store', 'update']);

        $this->middleware('scope:manage-products');

        $this->middleware('can:view,seller')->only('index');
        $this->middleware('can:sell,seller')->only('store');
        $this->middleware('can:edit-product,seller')->only('update');
        $this->middleware('can:delete-product,seller')->only('destroy');


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $seller)
    {
        if(request()->user()->tokenCan('read-general') || request()->user()->tokenCan('manage-products')) {
            $sellerProducts = $seller->product;
            return $this->showAll($sellerProducts);
        }
        throw new AuthorizationException('Invalid scope(s)');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required|max:150',
            'description' => 'required|max:1000',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $data = $request->all();

        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('products');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product, 'Following product created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(User $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(User $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $seller, Product $product)
    {
        $rules = [
            'name' => 'max:150',
            'description' => 'max:1000',
            'quantity' => 'integer|min:1',
            'image' => 'image'
        ];

        $this->validate($request, $rules);
        $this->checkUser($seller, $product);

        $product->fill($request->intersect([
             'name',
            'description',
            'quantity',
        ]));

        if($request->has('status')) {
            $product->status = $request->input('name');

            if($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must
                     have at leat one category', 409);
            }
        }

        if($request->hasFile('image')) {

            Storage::delete($product->image);

            $product->image = $request->image->store('products');
        }

        if($product->isClean()) {
            return $this->errorResponse('You need to specify different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $seller, Product $product)
    {
        $this->checkUser($seller, $product);
        Storage::delete($product->image);
        $product->delete();
    }

    public function checkUser(User $seller, Product $product) {

        if($seller->id !== $product->seller_id) {
            throw new HttpException(422, 'Only owner of the product can modify it');
        }
    }
}
