<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    protected function products() {
        return $this->hasMany(Product::class);
    }
}
