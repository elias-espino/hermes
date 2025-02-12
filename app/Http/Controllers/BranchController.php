<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    /**
     * Muestra todas las sucursales de una empresa (admins ven todas, managers solo las de sus empresas).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->usertype === 1) { // Admin ve todas las sucursales
            $branches = Branch::all();
        } else { // Managers solo ven las de sus empresas
            $branches = Branch::whereHas('company', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->get();
        }

        return response()->json($branches);
    }

    /**
     * Crea una nueva sucursal (solo managers y dentro de sus empresas).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->type_id !== 2) { // Solo managers pueden crear sucursales
            return response()->json(['error' => 'No autorizado'], 403);
        }

     /*   $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:20',
            'iva' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50|unique:branches,email',
        ]);*/

        // Verificar que el manager sea dueño de la empresa
        $company = Company::where('id', $request->company_id)
            ->where('manager_id', $user->id)
            ->first();

        if (!$company) {
            return response()->json(['error' => 'No puedes agregar sucursales a esta empresa'], 403);
        }

        $branch = Branch::create([
            'id' => Str::uuid(),
            'company_id' => $request->company_id,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'iva' => $request->iva,
            'email' => $request->email,
        ]);

        return response()->json($branch, 201);
    }

    /**
     * Muestra una sucursal específica (admins y managers de la empresa).
     */
    public function show($id)
    {
        $user = Auth::user();
        $branch = Branch::findOrFail($id);

        if ($user->usertype !== 1 && $branch->company->manager_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($branch);
    }

    /**
     * Actualiza una sucursal (solo el manager dueño de la empresa).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $branch = Branch::findOrFail($id);

        if ($branch->company->manager_id !== $user->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'direccion' => 'sometimes|string|max:250',
            'telefono' => 'nullable|string|max:20',
            'iva' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50|unique:branches,email,' . $id,
        ]);

        $branch->update($request->all());

        return response()->json($branch);
    }

    /**
     * Elimina una sucursal (Soft Delete, solo admins).
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $branch = Branch::findOrFail($id);

        if ($user->usertype !== 1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $branch->delete();
        return response()->json(['message' => 'Sucursal eliminada']);
    }
}

