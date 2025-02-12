<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchUser extends Model {
    use SoftDeletes;

    protected $fillable = ['branch_id', 'user_id'];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

