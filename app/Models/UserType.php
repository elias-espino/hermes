<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model {
    protected $fillable = ['name'];

    public $timestamps = false;

    public function users() {
        return $this->hasMany(User::class, 'type_id');
    }
}

