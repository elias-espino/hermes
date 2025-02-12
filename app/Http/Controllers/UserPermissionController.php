<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class UserPermissionController extends Controller {
    public function assignPermissions(Request $request, $userId) {
        $user = User::findOrFail($userId);
        $userAuth = Auth::user();
        // Solo Managers pueden asignar permisos
        if ($userAuth->type_id != 2) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        $permissions = Permission::whereIn('name', $request->input('permissions', []))->pluck('id');

        $user->permissions()->sync($permissions);

        return response()->json(['message' => 'Permisos asignados correctamente']);
    }
}
