<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['task', 'order', 'user_id', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
