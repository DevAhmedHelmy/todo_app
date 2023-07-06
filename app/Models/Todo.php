<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = [];

    // make scope by login user
    public function scopeByUser($query)
    {

        return $query->where('user_id', auth('api')->id());
    }
}
