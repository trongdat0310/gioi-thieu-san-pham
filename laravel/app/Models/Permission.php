<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_id',
        'permission_code',
        'permission_name',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    public function getDataJson()
    {
        $data = [
            "id" => $this->id,
            'org_id' => $this->org_id,
            'permission_code' => $this->permission_code,
            'permission_name' => $this->permission_name,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            'created_by' => $this->created_by,
            'last_updated_by' => $this->last_updated_by,
        ];

        return $data;
    }
}
