<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'org_id',
        'user_id',
        'role_id',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];

    // Mối quan hệ với bảng `users`
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mối quan hệ với bảng `roles`
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
