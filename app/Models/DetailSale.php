<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSale extends Model
{
    use HasFactory;

    protected $table = 'detail_sales';
    protected $primaryKey = 'detail_sale_id';
    protected $guarded = [];

    public function produk()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }
}
