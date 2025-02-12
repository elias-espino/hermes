<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class PermissionMiddleware {
    public function handle(Request $request, Closure $next, $permissions) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token no encontrado'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        // Permitir múltiples permisos separados por "|"
        $permissionList = explode('|', $permissions);

        foreach ($permissionList as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'No tienes permisos para esta acción'], Response::HTTP_FORBIDDEN);
    }
}

