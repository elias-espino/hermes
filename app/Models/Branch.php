<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model {
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'company_id', 'nombre', 'direccion', 'telefono', 'iva', 'email'];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'branch_users');
    }
}
