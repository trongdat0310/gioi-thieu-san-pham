<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'org_id',
        'media_type',
        'media_path',
        'media_code',
        'media_name',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];

    public function getDataJson()
    {
        $data = [
            "id" => $this->id,
            "organization" => $this->org_id,
            'media_type' => $this->media_type,
            'media_path' => $this->media_path,
            'media_code' => $this->media_code,
            'media_name' => $this->media_name,
            'product' => $this->product,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            'created_by' => $this->created_by,
            'last_updated_by' => $this->last_updated_by,
        ];

        return $data;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'media_products', 'media_id', 'product_id');
    }
}
