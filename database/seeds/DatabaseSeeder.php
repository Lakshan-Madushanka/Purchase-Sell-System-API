<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // $this->call(UserSeeder::class);
        User::truncate();
        Product::truncate();
        Category::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $usersQuantity = 100;
        $productssQuantity = 300;
        $categoriessQuantity = 20;
        $transactionsQuantity = 500;

        factory(User::class, $usersQuantity)->create();
       factory(Category::class, $categoriessQuantity)->create();
        factory(Product::class, $productssQuantity)->create()->each(
            function ($product) {
                $categories = Category::all()->random(mt_rand(1, 15))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
       // factory(Transaction::class, $transactionsQuantity)->create();

    }
}
