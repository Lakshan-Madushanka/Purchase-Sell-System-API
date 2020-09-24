<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Seller;
use App\Transaction;
use App\User;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {


    $seller = Seller::has('products')->get('id');
   // echo('hero ');
    //echo(gettype($seller));
    //print_r($seller->toArray());
    $selleyIDArray = $seller->toArray();
    //print_r($selleyIDArray);
      // print_r(User::all());
   $buyer = User::whereNotIn('id',$selleyIDArray)->get()->random();
print_r($buyer->id);
    return [
        'quantity' => $faker->numberBetween(1, 10),
        'buyer_id' => $buyer->id,
        'product_id' => Seller::all()->random()->id

    ];
});
