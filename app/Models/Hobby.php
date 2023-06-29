<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;

    protected $table = 'hobbies';

    protected $fillable = [
        'name',
    ];
    public function users()
{
    return $this->belongsToMany(User::class, 'user_hobbies', 'hobby_id', 'user_id');
}
}
