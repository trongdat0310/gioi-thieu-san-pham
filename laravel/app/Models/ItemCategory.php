<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'parent_category_id',
        'category_id',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];
}
