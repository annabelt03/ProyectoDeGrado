<?php

namespace App\Http\Controllers\Admin;

use App\Models\RegistroPuntos;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PuntosController extends Controller
{
    /**
     * Mostrar el dashboard de puntos para admin
     */
    public function index(Request $request)
    {
        $query = RegistroPuntos::with('usuario')
            ->orderBy('created_at', 'desc');

        // Filtro por usuario
        if ($request->has('usuario_id') && $request->usuario_id) {
            $query->where('usuario_id', $request->usuario_id);
        }

        // Filtro por fecha
        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('created_at', $request->fecha);
        }

        $registros = $query->paginate(20);
        $usuarios = Usuario::where('estado', 'activo')->get();

        // Estadísticas
        $totalPuntos = RegistroPuntos::sum('puntos_asignados');
        $promedioPuntos = RegistroPuntos::avg('puntos_asignados');
        $totalRegistros = RegistroPuntos::count();

        return view('administrador.puntos.index', compact(
            'registros',
            'usuarios',
            'totalPuntos',
            'promedioPuntos',
            'totalRegistros'
        ));
    }

    /**
     * Mostrar estadísticas de puntos
     */
    public function estadisticas()
    {
        // Puntos por día (últimos 30 días)
        $puntosPorDia = RegistroPuntos::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('SUM(puntos_asignados) as total_puntos'),
                DB::raw('COUNT(*) as total_registros')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->get();

        // Top usuarios por puntos
        $topUsuarios = Usuario::select('id', 'nombre', 'primerApellido', 'puntos')
            ->where('puntos', '>', 0)
            ->orderBy('puntos', 'desc')
            ->limit(10)
            ->get();

        // Distribución de pesos
        $distribucionPesos = RegistroPuntos::select(
            DB::raw('CASE
                WHEN peso_gramos < 100 THEN "0-100g"
                WHEN peso_gramos BETWEEN 100 AND 500 THEN "100-500g"
                WHEN peso_gramos BETWEEN 500 AND 1000 THEN "500-1000g"
                ELSE "Más de 1000g"
            END as rango_peso'),
            DB::raw('COUNT(*) as total'),
            DB::raw('AVG(puntos_asignados) as promedio_puntos')
        )
        ->groupBy('rango_peso')
        ->get();

        return view('administrador.puntos.estadisticas', compact(
            'puntosPorDia',
            'topUsuarios',
            'distribucionPesos'
        ));
    }

    /**
     * Mostrar detalles de un registro específico
     */
    public function show($id)
    {
        $registro = RegistroPuntos::with('usuario')->findOrFail($id);
        return view('administrador.puntos.show', compact('registro'));
    }

    /**
     * Eliminar un registro de puntos (admin only)
     */
    public function destroy($id)
    {
        $registro = RegistroPuntos::findOrFail($id);

        // Restar puntos al usuario si está asociado
        if ($registro->usuario_id) {
            $usuario = Usuario::find($registro->usuario_id);
            if ($usuario) {
                $usuario->puntos -= $registro->puntos_asignados;
                $usuario->save();
            }
        }

        $registro->delete();

        return redirect()->route('administrador.puntos.index')
            ->with('success', 'Registro de puntos eliminado correctamente');
    }
}
