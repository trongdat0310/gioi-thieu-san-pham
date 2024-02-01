<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'org_id',
        'role_code',
        'role_name',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];

    public function getDataJson()
    {
        $data = [
            "id" => $this->id,
            'org_id' => $this->org_id,
            'role_code' => $this->role_code,
            'role_name' => $this->role_name,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            'created_by' => $this->created_by,
            'last_updated_by' => $this->last_updated_by,
        ];

        return $data;
    }

    // Mối quan hệ với bảng `users`
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    // Mối quan hệ với bảng `organizations` thông qua bảng `users`
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }
}
