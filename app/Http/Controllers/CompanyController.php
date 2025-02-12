<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Muestra todas las empresas (solo admins pueden ver todas).
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->usertype === 1) { // Admin puede ver todas
            $companies = Company::all();
        } else { // Managers solo ven sus empresas
            $companies = Company::where('manager_id', $user->id)->get();
        }

        return response()->json($companies);
    }

    /**
     * Crea una nueva empresa (solo managers pueden).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->type_id !== 2) { // 2 = Manager
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:20',
            'iva' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50|unique:companies,email',
        ]);

        $company = Company::create([
            'id' => Str::uuid(),
            'manager_id' => $user->id,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'iva' => $request->iva,
            'email' => $request->email,
        ]);

        return response()->json($company, 201);
    }

    /**
     * Muestra una empresa específica (solo admins y el manager dueño).
     */
    public function show($id)
    {
        $user = Auth::user();
        $company = Company::findOrFail($id);

        if ($user->usertype !== 1 && $company->manager_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($company);
    }

    /**
     * Actualiza una empresa (solo el manager dueño).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $company = Company::findOrFail($id);

        if ($company->manager_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'direccion' => 'sometimes|string|max:250',
            'telefono' => 'nullable|string|max:20',
            'iva' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50|unique:companies,email,' . $id,
        ]);

        $company->update($request->all());

        return response()->json($company);
    }

    /**
     * Elimina una empresa (Soft Delete, solo admins).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $company = Company::findOrFail($id);

        if ($user->usertype !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $company->delete();
        return response()->json(['message' => 'Empresa eliminada']);
    }
}
