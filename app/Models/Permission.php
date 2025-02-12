<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model {
    use SoftDeletes;

    protected $fillable = ['name'];

    public function users() {
        return $this->belongsToMany(User::class, 'user_permissions');
    }
}

