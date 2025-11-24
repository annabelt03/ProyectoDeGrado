<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Usuario;
use App\Models\RegistroPuntos;
use App\Models\Registro;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    public function index()
    {
        return view('estadisticas.index');
    }

    public function puntos(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subDays(30)->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->format('Y-m-d'));

        // Estadísticas generales de puntos
        $estadisticasPuntos = [
            'totalPuntosAsignados' => RegistroPuntos::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('puntos_asignados'),
            'totalRegistros' => RegistroPuntos::whereBetween('created_at', [$fechaInicio, $fechaFin])->count(),
            'promedioPuntosPorRegistro' => RegistroPuntos::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->avg('puntos_asignados') ?? 0,
            'pesoTotal' => RegistroPuntos::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->sum('peso_gramos') / 1000, // Convertir a kg
        ];

        // Puntos por día (últimos 30 días)
        $puntosPorDia = RegistroPuntos::whereBetween('created_at', [
            Carbon::now()->subDays(30),
            Carbon::now()
        ])
            ->selectRaw('DATE(created_at) as fecha, SUM(puntos_asignados) as total_puntos')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Top usuarios por puntos
        $topUsuarios = Usuario::withSum(['registroPuntos as puntos_totales' => function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }], 'puntos_asignados')
            ->orderBy('puntos_totales', 'desc')
            ->take(10)
            ->get();

        return view('estadisticas.puntos', compact(
            'estadisticasPuntos',
            'puntosPorDia',
            'topUsuarios',
            'fechaInicio',
            'fechaFin'
        ));
    }

    public function canjes(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subDays(30)->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->format('Y-m-d'));

        // Estadísticas generales de canjes
        $estadisticasCanjes = [
            'totalCanjes' => Registro::whereBetween('created_at', [$fechaInicio, $fechaFin])->count(),
            'totalPuntosCanjeados' => Registro::whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('puntos_totales'),
            'productosCanjeados' => Registro::whereBetween('created_at', [$fechaInicio, $fechaFin])->sum('cantidad'),
        ];

        // Canjes por día
        $canjesPorDia = Registro::whereBetween('created_at', [
            Carbon::now()->subDays(30),
            Carbon::now()
        ])
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total_canjes, SUM(puntos_totales) as puntos_totales')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Productos más populares
        $productosPopulares = Producto::withCount(['registros as total_canjeos' => function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }])
            ->withSum(['registros as puntos_totales' => function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            }], 'puntos_totales')
            ->orderBy('total_canjeos', 'desc')
            ->take(10)
            ->get();

        // Usuarios que más canjean
        $usuariosActivos = Usuario::withCount(['registros as total_canjes' => function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }])
            ->withSum(['registros as puntos_canjeados' => function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            }], 'puntos_totales')
            ->orderBy('total_canjes', 'desc')
            ->take(10)
            ->get();

        return view('estadisticas.canjes', compact(
            'estadisticasCanjes',
            'canjesPorDia',
            'productosPopulares',
            'usuariosActivos',
            'fechaInicio',
            'fechaFin'
        ));
    }

    public function usuarios()
    {
        $estadisticasUsuarios = [
            'totalUsuarios' => Usuario::count(),
            'usuariosActivos' => Usuario::where('estado', 'activo')->count(),
            'usuariosInactivos' => Usuario::where('estado', 'inactivo')->count(),
            'usuariosSuspendidos' => Usuario::where('estado', 'suspendido')->count(),
            'totalEstudiantes' => Usuario::where('role', 'estudiante')->count(),
            'totalAdministradores' => Usuario::where('role', 'administrador')->count(),
        ];

        // Distribución por género
        $distribucionGenero = Usuario::select('genero', DB::raw('COUNT(*) as total'))
            ->whereNotNull('genero')
            ->groupBy('genero')
            ->get();

        // Usuarios por rango de puntos
        $rangosPuntos = [
            '0-100' => Usuario::whereBetween('puntos', [0, 100])->count(),
            '101-500' => Usuario::whereBetween('puntos', [101, 500])->count(),
            '501-1000' => Usuario::whereBetween('puntos', [501, 1000])->count(),
            '1001+' => Usuario::where('puntos', '>', 1000)->count(),
        ];

        // Usuarios recientemente registrados (últimos 30 días)
        $usuariosRecientes = Usuario::where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('estadisticas.usuarios', compact(
            'estadisticasUsuarios',
            'distribucionGenero',
            'rangosPuntos',
            'usuariosRecientes'
        ));
    }

    public function apiPuntosDiarios()
    {
        $puntos = RegistroPuntos::where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as fecha, SUM(puntos_asignados) as total_puntos')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return response()->json($puntos);
    }

    public function apiCanjesDiarios()
    {
        $canjes = Registro::where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total_canjes')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return response()->json($canjes);
    }
}
