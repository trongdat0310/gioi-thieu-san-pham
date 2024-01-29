<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_code',
        'org_name',
        'start_date',
        'end_date',
        'created_by',
        'last_updated_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'org_id');
    }
}
