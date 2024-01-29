<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'org_id',
        'product_code',
        'product_name',
        'product_description',
        'primary_price',
        'product_sku',
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
            'product_code' => $this->product_code,
            'product_name' => $this->product_name,
            'product_description' => $this->product_description,
            'primary_price' => $this->primary_price,
            'product_sku' => $this->product_sku,
            'media' => $this->media,
            'category' => $this->category,
            'attribute' => $this->attribute,
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

    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_products', 'product_id', 'media_id');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_products', 'product_id', 'category_id');
    }

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'item_attributes', 'item_id', 'attribute_id')->where('item_type', 'prod');
    }
}
