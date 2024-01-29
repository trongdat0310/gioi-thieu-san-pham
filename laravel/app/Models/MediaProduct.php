<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'product_id',
        'media_id',
        'primary_flag',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];
}
