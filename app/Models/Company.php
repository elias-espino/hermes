<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model {
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'manager_id', 'nombre', 'direccion', 'telefono', 'iva', 'email'];

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function branches() {
        return $this->hasMany(Branch::class, 'company_id');
    }
}

