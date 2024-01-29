<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'org_id',
        'category_code',
        'category_name',
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
            'category_code' => $this->category_code,
            'category_name' => $this->category_name,
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
        return $this->belongsToMany(Product::class, 'category_products', 'category_id', 'product_id');
    }

    public function parent()
    {
        return $this->belongsToMany(ItemCategory::class, 'category_products', 'category_id', 'product_id');
    }
}
