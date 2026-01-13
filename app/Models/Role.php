<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $fillable = [
        'name', 'description'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
