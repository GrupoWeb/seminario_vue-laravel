<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userHasRoles extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id', 'users_id'
    ];
}
