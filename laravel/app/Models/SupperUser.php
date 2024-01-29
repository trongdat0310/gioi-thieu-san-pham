<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupperUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
