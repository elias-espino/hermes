<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPermission extends Model {
    use SoftDeletes;

    protected $fillable = ['user_id', 'permission_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function permission() {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}

