<?php

// app/Http/Controllers/Admin/UsuarioController.php
namespace App\Http\Controllers\Admin;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsuarioController extends BaseAdminController
{
    /**
     * Index retrocompatible.
     */
    public function index()
    {
        $role     = request('role');                     // usuario | administrador | null
        $sort     = request('sort', 'latest');           // latest | nombre
        $perPage  = (int) request('per_page', 0);

        $query = Usuario::query();

        if ($role && in_array($role, ['estudiante','administrador'], true)) {
            $query->where('role', $role);
            $perPage = $perPage > 0 ? $perPage : 15;
        } else {
            $perPage = $perPage > 0 ? $perPage : 12;
        }

        if ($sort === 'nombre') {
            $query->orderBy('nombre');
        } else {
            $query->orderByDesc('id');
        }

        $usuarios = $query->paginate($perPage);

        return view('auth.admin.usuarios.index', compact('usuarios','role'));
    }

    public function create()
    {
        // Vista de creación del admin (no usar auth.register)
        return view('auth.admin.usuarios.create');
    }

public function store(Request $r)
{
    $r->validate([
    'role'            => ['required', Rule::in(['administrador','estudiante'])],

    'nombre'          => 'required|string|max:120',
    'primerApellido'  => 'required|string|max:120',
    'segundoApellido' => 'nullable|string|max:120',

    // Solo ADMIN
    'email' => [
        'exclude_unless:role,administrador',
        'required',
        'email',
        'unique:usuarios,email',
    ],
    'password' => [
        'exclude_unless:role,administrador',
        'required',
        'min:8',
        'confirmed',
    ],
    'password_confirmation' => [
        'exclude_unless:role,administrador',
        'required',
        'min:8',
    ],

    // Solo ESTUDIANTE
    'numeroRFID' => [
        'exclude_unless:role,estudiante',
        'required',
        'regex:/^[A-Za-z0-9_-]{4,32}$/',
        Rule::unique('usuarios','numeroRFID'),
    ],
]);


    // Password:
    // - admin: la que mandó el form
    // - estudiante: lo autogeneramos con su RFID (no lo pide el form)
    $password = $r->role === 'administrador'
        ? Hash::make($r->password)
        : Hash::make((string) $r->numeroRFID);

    $usuario = Usuario::create([
        'nombre'          => $r->nombre,
        'primerApellido'  => $r->primerApellido,
        'segundoApellido' => $r->segundoApellido,
        'email'           => $r->role === 'administrador' ? $r->email : null,
        'password'        => $password,
        'role'            => $r->role,
        'estado'          => 'activo',
        'numeroRFID'      => $r->role === 'estudiante' ? (string)$r->numeroRFID : null,
        'fechaNacimiento' => null,
        'genero'          => null,
        'puntos'          => 0,
    ]);

    return redirect()->route('admin.usuarios.index')->with('ok', 'Usuario creado.');
}

   public function update(Request $r, Usuario $usuario)
{
    $r->validate([
    'role'            => ['required', Rule::in(['administrador','estudiante'])],
    'estado'          => ['required', Rule::in(['activo','inactivo','suspendido'])],

    'nombre'          => 'required|string|max:120',
    'primerApellido'  => 'required|string|max:120',
    'segundoApellido' => 'nullable|string|max:120',

    // Solo ADMIN
    'email' => [
        'exclude_unless:role,administrador',
        'required',
        'email',
        Rule::unique('usuarios','email')->ignore($usuario->id),
    ],
    'password' => [
        'exclude_unless:role,administrador',
        'nullable',     // en update puede ser opcional
        'min:8',
        'confirmed',
    ],
    'password_confirmation' => [
        'exclude_unless:role,administrador',
        'nullable',
        'min:8',
    ],

    // Solo ESTUDIANTE
    'numeroRFID' => [
        'exclude_unless:role,estudiante',
        'required',
        'regex:/^[A-Za-z0-9_-]{4,32}$/',
        Rule::unique('usuarios','numeroRFID')->ignore($usuario->id),
    ],
]);


    // Después de validar, preparamos los datos
    $data = [
        'nombre'          => $r->nombre,
        'primerApellido'  => $r->primerApellido,
        'segundoApellido' => $r->segundoApellido,
        'role'            => $r->role,
        'estado'          => $r->estado,
        'email'           => $r->role === 'administrador' ? $r->email : null,
        'numeroRFID'      => $r->role === 'estudiante' ? (string)$r->numeroRFID : null,
    ];

    if ($r->filled('password') && $r->role === 'administrador') {
        $data['password'] = Hash::make($r->password);
    }

    // Reglas extra de transición de rol (opcional pero recomendable):
    // - Si cambia a ADMIN y no envía password y el admin no tenía, puedes forzar error:
    if ($usuario->role !== 'administrador' && $r->role === 'administrador' && !$r->filled('password') && !$usuario->password) {
        return back()->withErrors(['password' => 'Al convertir a administrador, debe establecer una contraseña.'])->withInput();
    }

    $usuario->update($data);

    return back()->with('ok', 'Usuario actualizado.');
}


    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return back()->with('ok','Usuario eliminado.');
    }

    public function show(Usuario $usuario)
    {
        return view('auth.admin.usuarios.show', compact('usuario'));
    }
}
