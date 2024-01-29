<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'category_id',
        'product_id',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];
}
