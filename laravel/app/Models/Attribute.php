<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'attribute_code',
        'attribute_name',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];
}
