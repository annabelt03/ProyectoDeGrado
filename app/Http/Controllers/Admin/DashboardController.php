<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Registro;
use App\Models\RegistroCanjeo;
use App\Models\RegistroPuntos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
public function index(Request $request)
    {
        // Verificar el rol del usuario
        $usuario = auth()->user();

        if ($usuario->role === 'administrador') {
            return $this->adminDashboard($request);
        } elseif ($usuario->role === 'estudiante') {
            return $this->estudianteDashboard($request);
        }

        abort(403, 'Acceso no autorizado');
    }

    /**
     * Dashboard para Administradores
     */
    private function adminDashboard(Request $request)
    {
        // ... (tu código existente para admin se mantiene igual)
        $totalUsuarios = Usuario::count();
        $estudiantesActivos = Usuario::where('role', 'estudiante')
                                   ->where('estado', 'activo')
                                   ->count();
        $totalAdministradores = Usuario::where('role', 'administrador')->count();
        $usuariosInactivos = Usuario::where('estado', '!=', 'activo')->count();
        $usuariosRecientes = Usuario::latest()->take(5)->get();

        // Estadísticas de productos
        $totalProductos = Producto::count();
        $productosActivos = Producto::where('activo', true)->count();
        $productosStockBajo = Producto::where('stock', '<=', 5)->count();
        $productosAgotados = Producto::where('stock', 0)->count();

        // Estadísticas de canjes
        $totalCanjes = RegistroCanjeo::count();
        $canjesHoy = RegistroCanjeo::whereDate('fecha_canjeo', today())->count();
        $puntosCanjeadosHoy = RegistroCanjeo::whereDate('fecha_canjeo', today())->sum('puntos_totales');
        $puntosCanjeadosTotal = RegistroCanjeo::sum('puntos_totales');

        // Estados de canjes
        $estadosCanjes = RegistroCanjeo::selectRaw('estado, count(*) as total')
                                ->groupBy('estado')
                                ->get();

        // Top productos más canjeados
        $topProductos = RegistroCanjeo::selectRaw('producto_id, count(*) as total_canjes, sum(cantidad) as total_unidades')
                               ->with('producto')
                               ->groupBy('producto_id')
                               ->orderBy('total_canjes', 'desc')
                               ->limit(5)
                               ->get();

        // Últimos canjes
        $ultimosCanjes = RegistroCanjeo::with(['usuario', 'producto'])
                                ->orderBy('fecha_canjeo', 'desc')
                                ->limit(5)
                                ->get();

        return view('auth.admin.dashboard', compact(
            'totalUsuarios',
            'estudiantesActivos',
            'totalAdministradores',
            'usuariosInactivos',
            'usuariosRecientes',
            'totalProductos',
            'productosActivos',
            'productosStockBajo',
            'productosAgotados',
            'totalCanjes',
            'canjesHoy',
            'puntosCanjeadosHoy',
            'puntosCanjeadosTotal',
            'estadosCanjes',
            'topProductos',
            'ultimosCanjes'
        ));
    }

    /**
     * Dashboard para Estudiantes - MEJORADO
     */
    private function estudianteDashboard(Request $request)
    {
        try {
            $estudiante = auth()->user();

            // Verificar que el usuario sea estudiante
            if ($estudiante->role !== 'estudiante') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            // Puntos totales del estudiante
            $puntosTotales = $this->calcularPuntosDisponibles($estudiante->id);

            // Últimos registros de puntos
            $ultimosRegistros = RegistroPuntos::where('usuario_id', $estudiante->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Productos disponibles
            $productosDisponibles = Producto::where('activo', true)
                ->where('stock', '>', 0)
                ->orderBy('puntos_valor', 'asc')
                ->get();

            // Historial de canjes del estudiante
            $historialCanjes = RegistroCanjeo::where('usuario_id', $estudiante->id)
                ->with('producto')
                ->orderBy('fecha_canjeo', 'desc')
                ->take(5)
                ->get();

            return view('estudiante.dashboard', [
                'estudiante' => $estudiante,
                'puntosTotales' => $puntosTotales,
                'ultimosRegistros' => $ultimosRegistros,
                'productosDisponibles' => $productosDisponibles,
                'historialCanjes' => $historialCanjes
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en estudianteDashboard: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Error al cargar el dashboard');
        }
    }

    /**
     * Método para que el estudiante canjee productos
     */
    public function canjearProducto(Request $request, $productoId)
    {
        try {
            $estudiante = auth()->user();

            if ($estudiante->role !== 'estudiante') {
                return redirect()->back()->with('error', 'Acceso no autorizado');
            }

            $producto = Producto::findOrFail($productoId);

            // Verificar que el producto esté disponible
            if (!$producto->activo || $producto->stock <= 0) {
                return redirect()->back()->with('error', 'Este producto no está disponible');
            }

            // Calcular puntos totales del estudiante
            $puntosTotales = RegistroPuntos::where('usuario_id', $estudiante->id)
                ->sum('puntos_asignados');

            // Verificar si tiene puntos suficientes
            if ($puntosTotales < $producto->puntos_valor) {
                return redirect()->back()->with('error',
                    'No tienes suficientes puntos. Necesitas: ' . $producto->puntos_valor .
                    ', Tienes: ' . $puntosTotales
                );
            }

            // Iniciar transacción para asegurar consistencia
            DB::beginTransaction();

            try {
                // Crear registro de canjeo
                $canjeo = RegistroCanjeo::create([
                    'usuario_id' => $estudiante->id,
                    'producto_id' => $producto->id,
                    'cantidad' => 1,
                    'puntos_totales' => $producto->puntos_valor,
                    'fecha_canjeo' => now(),
                    'estado' => 'completado'
                ]);

                // Reducir el stock del producto
                $producto->decrement('stock');

                DB::commit();

                return redirect()->back()->with('success',
                    '¡Canjeo exitoso! Has canjeado "' . $producto->nombreProducto .
                    '" por ' . $producto->puntos_valor . ' puntos.'
                );

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Error durante el canjeo: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si puede canjear un producto (para modal o verificación previa)
     */
    public function verificarCanjeo($productoId)
    {
        try {
            $estudiante = auth()->user();

            if ($estudiante->role !== 'estudiante') {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso no autorizado'
                ], 403);
            }

            $producto = Producto::findOrFail($productoId);

            if (!$producto->activo || $producto->stock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no disponible'
                ], 400);
            }

            // Calcular puntos totales actuales
            $puntosTotales = RegistroPuntos::where('usuario_id', $estudiante->id)
                ->sum('puntos_asignados');

            $puedeCanjear = $puntosTotales >= $producto->puntos_valor;

            return response()->json([
                'success' => true,
                'data' => [
                    'puede_canjear' => $puedeCanjear,
                    'puntos_actuales' => $puntosTotales,
                    'puntos_requeridos' => $producto->puntos_valor,
                    'diferencia' => $puntosTotales - $producto->puntos_valor,
                    'producto' => [
                        'id' => $producto->id,
                        'nombre' => $producto->nombreProducto,
                        'descripcion' => $producto->descripcion,
                        'imagen' => $producto->imagen
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar canjeo: ' . $e->getMessage()
            ], 500);
        }
    }




    /**
     * Estadísticas avanzadas para admin (opcional)
     */
    public function estadisticasAvanzadas(Request $request)
    {
        // Solo para administradores
        if (auth()->user()->role !== 'administrador') {
            abort(403, 'No autorizado');
        }

        $rango = $request->get('range', 'week');

        // Lógica para estadísticas por rango de tiempo
        $canjesPorMes = $this->getCanjesPorMes($rango);
        $topRecolectores = $this->getTopRecolectores($rango);
        $productosPopulares = $this->getProductosPopulares($rango);

        return view('dashboard.estadisticas', compact(
            'canjesPorMes',
            'topRecolectores',
            'productosPopulares',
            'rango'
        ));
    }

    private function getCanjesPorMes($rango)
    {
        $query = RegistroCanjeo::selectRaw('YEAR(fecha_canjeo) as year, MONTH(fecha_canjeo) as month, COUNT(*) as total');

        if ($rango === 'week') {
            $query->where('fecha_canjeo', '>=', now()->subWeek());
        } elseif ($rango === 'month') {
            $query->where('fecha_canjeo', '>=', now()->subMonth());
        }

        return $query->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->get();
    }

    private function getTopRecolectores($rango)
    {
        $query = Usuario::where('role', 'estudiante')
                       ->withCount(['registros as total_puntos' => function($query) use ($rango) {
                           if ($rango === 'week') {
                               $query->where('fecha_canjeo', '>=', now()->subWeek());
                           } elseif ($rango === 'month') {
                               $query->where('fecha_canjeo', '>=', now()->subMonth());
                           }
                           $query->select(DB::raw('COALESCE(SUM(puntos_totales), 0)'));
                       }])
                       ->orderBy('total_puntos', 'desc')
                       ->limit(10);

        return $query->get();
    }

    private function getProductosPopulares($rango)
    {
        $query = Producto::withCount(['registros as total_canjes' => function($query) use ($rango) {
                        if ($rango === 'week') {
                            $query->where('fecha_canjeo', '>=', now()->subWeek());
                        } elseif ($rango === 'month') {
                            $query->where('fecha_canjeo', '>=', now()->subMonth());
                        }
                    }])
                    ->orderBy('total_canjes', 'desc')
                    ->limit(10);

        return $query->get();
    }

    private function calcularPuntosDisponibles($usuarioId)
    {
        $puntosObtenidos = RegistroPuntos::where('usuario_id', $usuarioId)
            ->sum('puntos_asignados');

        $puntosCanjeados = RegistroCanjeo::where('usuario_id', $usuarioId)
            ->where('estado', 'completado')
            ->sum('puntos_totales');

        return $puntosObtenidos - $puntosCanjeados;
    }

}
