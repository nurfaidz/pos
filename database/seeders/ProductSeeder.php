<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Categorie::create([
            'category_name' => 'Makanan',
        ]);

        $category1 = Categorie::create([
            'category_name' => 'Minuman',
        ]);

        Product::create([
            'product_code' => 'P01010',
            'category_id' => $category->category_id,
            'product_name' => 'Intel Goreng',
            'price' => '10000',
            'stock' => 15,
        ]);

        Product::create([
            'product_code' => 'P11111',
            'category_id' => $category1->category_id,
            'product_name' => 'Es Doger',
            'price' => '5000',
            'stock' => 15,
        ]);
    }
}
