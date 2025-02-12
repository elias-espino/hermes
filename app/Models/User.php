<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\UserType;
use App\Models\Permission;
use App\Models\Company;

class User extends Authenticatable implements JWTSubject {
    use Notifiable, SoftDeletes;

    protected $keyType = 'string'; // Para usar UUIDs
    public $incrementing = false; // No usar autoincrement
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'id',
        'type_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'telefono',
        'usuario',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];
    
    // Relación con UserType
    public function type() {
        return $this->belongsTo(UserType::class, 'type_id');
    }

    // Relación con permisos
    public function permissions() {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    // Relación con empresas si es manager
    public function companies() {
        return $this->hasMany(Company::class, 'manager_id');
    }

    // Relación con sucursales si es usuario de sucursal
    public function branches() {
        return $this->belongsToMany(Branch::class, 'branch_users');
    }

    // Función para verificar permisos basados en usertype
    public function hasPermission($permission) {
        $rolePermissions = [
            1 => ['all'], // Admins tienen acceso a todo
            2 => ['create_company', 'view_companies','create_branches'],
            3 => ['view_branch', 'create_branches'] // Se puede personalizar por usuario
        ];

        if ($this->type_id == 1 || $this->type_id == 2) {
           return true; // Admins tienen todos los permisos
        }

      // Permisos por usertype o asignados manualmente
    return in_array($permission, $rolePermissions[$this->type_id] ?? []) ;
    
    }

    // Métodos requeridos por JWT
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
