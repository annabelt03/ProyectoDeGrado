<?php

namespace App\Http\Controllers\Usuario;
use App\Http\Controllers\Controller;

use App\Models\Producto;
use App\Models\RegistroPuntos;
use App\Models\RegistroCanjeo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuntosCanjeosController extends Controller
{
    private function calcularPuntosDisponibles($usuarioId)
    {
        $puntosObtenidos = RegistroPuntos::where('usuario_id', $usuarioId)
            ->sum('puntos_asignados');

        $puntosCanjeados = RegistroCanjeo::where('usuario_id', $usuarioId)
            ->where('estado', 'completado')
            ->sum('puntos_totales');

        return $puntosObtenidos - $puntosCanjeados;
    }
    /**
     * VISTA: Mis Puntos - Historial completo de puntos
     */
    public function misPuntos()
    {
        try {
            $estudiante = auth()->user();

            if ($estudiante->role !== 'estudiante') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            // Puntos disponibles (obtenidos - canjeados)
            $puntosDisponibles = $this->calcularPuntosDisponibles($estudiante->id);

            // Puntos totales obtenidos (solo para estadísticas)
            $puntosObtenidos = RegistroPuntos::where('usuario_id', $estudiante->id)
                ->sum('puntos_asignados');

            // Puntos canjeados totales
            $puntosCanjeados = RegistroCanjeo::where('usuario_id', $estudiante->id)
                ->where('estado', 'completado')
                ->sum('puntos_totales');

            // Historial completo de puntos (paginado)
            $historialPuntos = RegistroPuntos::where('usuario_id', $estudiante->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            // Historial de canjes (para mostrar en estadísticas)
            $historialCanjes = RegistroCanjeo::where('usuario_id', $estudiante->id)
                ->with('producto')
                ->orderBy('fecha_canjeo', 'desc')
                ->take(5)
                ->get();

            // Estadísticas detalladas
            $estadisticas = [
                'puntos_disponibles' => $puntosDisponibles,
                'puntos_obtenidos' => $puntosObtenidos,
                'puntos_canjeados' => $puntosCanjeados,
                'total_registros' => RegistroPuntos::where('usuario_id', $estudiante->id)->count(),
                'total_canjes' => RegistroCanjeo::where('usuario_id', $estudiante->id)->count(),
                'registros_hoy' => RegistroPuntos::where('usuario_id', $estudiante->id)
                    ->whereDate('created_at', today())
                    ->count(),
                'puntos_hoy' => RegistroPuntos::where('usuario_id', $estudiante->id)
                    ->whereDate('created_at', today())
                    ->sum('puntos_asignados'),
                'puntos_semana' => RegistroPuntos::where('usuario_id', $estudiante->id)
                    ->whereDate('created_at', '>=', now()->subWeek())
                    ->sum('puntos_asignados'),
                'puntos_mes' => RegistroPuntos::where('usuario_id', $estudiante->id)
                    ->whereDate('created_at', '>=', now()->subMonth())
                    ->sum('puntos_asignados'),
                'promedio_puntos' => RegistroPuntos::where('usuario_id', $estudiante->id)
                    ->avg('puntos_asignados') ?? 0,
                'maximo_puntos' => RegistroPuntos::where('usuario_id', $estudiante->id)
                    ->max('puntos_asignados') ?? 0,
            ];

            return view('estudiante.mis-puntos', compact(
                'estudiante',
                'puntosDisponibles',
                'puntosObtenidos',
                'puntosCanjeados',
                'historialPuntos',
                'historialCanjes',
                'estadisticas'
            ));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al cargar los puntos: ' . $e->getMessage());
        }
    }

    /**
     * VISTA: Canjear Productos - Catálogo de productos para canjear
     */
    public function canjearProductos()
    {
        try {
            $estudiante = auth()->user();

            if ($estudiante->role !== 'estudiante') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            // Puntos disponibles (obtenidos - canjeados)
            $puntosDisponibles = $this->calcularPuntosDisponibles($estudiante->id);

            // Productos disponibles
            $productosDisponibles = Producto::where('activo', true)
                ->where('stock', '>', 0)
                ->orderBy('puntos_valor', 'asc')
                ->get();

            // Separar productos que puede canjear y los que no
            $productosCanjeables = [];
            $productosNoCanjeables = [];

            foreach ($productosDisponibles as $producto) {
                if ($puntosDisponibles >= $producto->puntos_valor) {
                    $productosCanjeables[] = $producto;
                } else {
                    $productosNoCanjeables[] = $producto;
                }
            }

            // Historial de canjes recientes
            $historialCanjes = RegistroCanjeo::where('usuario_id', $estudiante->id)
                ->with('producto')
                ->orderBy('fecha_canjeo', 'desc')
                ->take(5)
                ->get();

            return view('estudiante.canjear-productos', compact(
                'estudiante',
                'puntosDisponibles',
                'productosCanjeables',
                'productosNoCanjeables',
                'historialCanjes'
            ));

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al cargar los productos: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si puede canjear un producto (AJAX)
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

            // Calcular puntos disponibles actuales
            $puntosDisponibles = $this->calcularPuntosDisponibles($estudiante->id);

            $puedeCanjear = $puntosDisponibles >= $producto->puntos_valor;

            return response()->json([
                'success' => true,
                'data' => [
                    'puede_canjear' => $puedeCanjear,
                    'puntos_actuales' => $puntosDisponibles, // ← Ahora usa puntos disponibles
                    'puntos_requeridos' => $producto->puntos_valor,
                    'diferencia' => $puntosDisponibles - $producto->puntos_valor,
                    'producto' => [
                        'id' => $producto->id,
                        'nombre' => $producto->nombreProducto,
                        'descripcion' => $producto->descripcion,
                        'imagen' => $producto->imagen,
                        'stock' => $producto->stock
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
     * Procesar canjeo de producto
     */
public function procesarCanjeo(Request $request, $productoId)
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

        // Calcular puntos disponibles dinámicamente
        $puntosDisponibles = $this->calcularPuntosDisponibles($estudiante->id);

        // Verificar si tiene puntos suficientes
        if ($puntosDisponibles < $producto->puntos_valor) {
            return redirect()->back()->with('error',
                'No tienes suficientes puntos. Necesitas: ' . $producto->puntos_valor .
                ', Tienes: ' . $puntosDisponibles
            );
        }

        // Iniciar transacción
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

            return redirect()->route('estudiante.canjear-productos')->with('success',
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

    public function historialPuntos()
    {
        try {
            $estudiante = auth()->user();

            if ($estudiante->role !== 'estudiante') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }
            $puntosDisponibles = $this->calcularPuntosDisponibles($estudiante->id);

            $historialPuntos = RegistroPuntos::where('usuario_id', $estudiante->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('estudiante.historial-puntos', compact('historialPuntos', 'estudiante','puntosDisponibles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar el historial: ' . $e->getMessage());
        }
    }

    /**
     * Obtener historial de canjes del estudiante
     */
    public function historialCanjes()
    {
        try {
            $estudiante = auth()->user();

            if ($estudiante->role !== 'estudiante') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }
            $puntosDisponibles = $this->calcularPuntosDisponibles($estudiante->id);

            $historialCanjes = RegistroCanjeo::where('usuario_id', $estudiante->id)
                ->with('producto')
                ->orderBy('fecha_canjeo', 'desc')
                ->paginate(15);

            return view('estudiante.historial-canjes', compact('historialCanjes', 'estudiante', 'puntosDisponibles'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar el historial de canjes: ' . $e->getMessage());
        }
    }
}
