<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Welcome público
    public function welcome() { return view('welcome'); }

    // Formularios
    public function showLogin() { return view('auth.login'); }
    public function showLoginAdmin() { return view('auth.loginAdmin'); }

    public function loginAdmin(Request $r)
    {
        $cred = $r->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($cred, true)) {
            return back()->withInput()->withErrors(['email' => 'Credenciales inválidas.']);
        }

        $r->session()->regenerate();

        if (auth()->user()->role !== 'administrador') {
            Auth::logout();
            return back()->withErrors(['email' => 'No tienes permisos de administrador.']);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function login(Request $r)
    {
        $data = $r->validate([
            'numeroRFID' => ['required', 'regex:/^[0-9a-fA-F]{8}$/'],
        ], [
            'numeroRFID.required' => 'El código RFID es obligatorio.',
            'numeroRFID.regex'    => 'El código RFID debe tener exactamente 8 caracteres (solo letras A–F y números 0–9).',
        ]);

        $user = Usuario::where('numeroRFID', strtolower($data['numeroRFID']))
                    ->where('estado', 'activo')
                    ->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'numeroRFID' => 'Código RFID inválido o usuario inactivo.',
            ]);
        }

        Auth::login($user, true);
        $r->session()->regenerate();

        // Redirigir según el rol
        if ($user->role === 'administrador') {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('estudiante.dashboard'));
        }
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}
