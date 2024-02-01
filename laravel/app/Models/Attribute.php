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

    public function getDataJson()
    {
        $data = [
            "id" => $this->id,
            "org_id" => $this->org_id,
            'attribute_code' => $this->attribute_code,
            'attribute_name' => $this->attribute_name,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            'created_by' => $this->created_by,
            'last_updated_by' => $this->last_updated_by,
        ];

        return $data;
    }
}
