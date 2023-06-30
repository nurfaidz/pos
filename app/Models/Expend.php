<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expend extends Model
{
    use HasFactory;

    protected $table = 'expends';
    protected $primaryKey = 'expend_id';
    protected $guarded = ['expend_id'];
}
