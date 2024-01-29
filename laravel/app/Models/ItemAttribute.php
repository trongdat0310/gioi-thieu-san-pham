<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'item_id',
        'attribute_id',
        'item_type',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];
}
