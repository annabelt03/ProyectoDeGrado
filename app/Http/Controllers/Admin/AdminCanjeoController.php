<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistroCanjeo as Registro;
use App\Models\Usuario;
use Illuminate\Http\Request;


class AdminCanjeoController extends Controller
{
    /**
     * Mostrar todos los canjeos (admin)
     */
    public function index(Request $request)
    {
        $query = Registro::with(['usuario', 'producto'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->has('usuario_id') && $request->usuario_id) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('fecha_canjeo', $request->fecha);
        }

        $canjeos = $query->paginate(20);
        $usuarios = Usuario::where('estado', 'activo')->get();
        $estados = ['canjeado', 'entregado', 'cancelado'];

        // Estadísticas
        $totalCanjeos = Registro::count();
        $canjeosPendientes = Registro::where('estado', 'canjeado')->count();
        $puntosCanjeados = Registro::where('estado', '!=', 'cancelado')->sum('puntos_totales');

        return view('administrador.canjeos.index', compact(
            'canjeos',
            'usuarios',
            'estados',
            'totalCanjeos',
            'canjeosPendientes',
            'puntosCanjeados'
        ));
    }

    /**
     * Actualizar estado de canjeo (entregado)
     */
    public function marcarEntregado($id)
    {
        $canjeo = Registro::findOrFail($id);

        if ($canjeo->estado !== 'canjeado') {
            return redirect()->back()
                ->with('error', 'Solo se pueden marcar como entregados canjeos en estado "canjeado"');
        }

        $canjeo->estado = 'entregado';
        $canjeo->save();

        return redirect()->back()
            ->with('success', 'Producto marcado como entregado');
    }

    /**
     * Cancelar canjeo (admin)
     */
    public function cancelar($id)
    {
        $canjeo = Registro::findOrFail($id);

        if ($canjeo->estado !== 'canjeado') {
            return redirect()->back()
                ->with('error', 'Solo se pueden cancelar canjeos en estado "canjeado"');
        }

        DB::transaction(function () use ($canjeo) {
            $usuario = $canjeo->usuario;
            $producto = $canjeo->producto;

            // Devolver puntos
            $usuario->puntos += $canjeo->puntos_totales;
            $usuario->save();

            // Devolver stock
            $producto->stock += 1;
            $producto->save();

            // Actualizar estado
            $canjeo->estado = 'cancelado';
            $canjeo->save();
        });

        return redirect()->back()
            ->with('success', 'Canjeo cancelado exitosamente');
    }

    /**
     * Estadísticas de canjeos
     */
    public function estadisticas()
    {
        // Productos más canjeados
        $productosPopulares = Registro::select('producto_id')
            ->selectRaw('COUNT(*) as total_canjeos')
            ->selectRaw('SUM(puntos_totales) as total_puntos')
            ->with('producto')
            ->where('estado', '!=', 'cancelado')
            ->groupBy('producto_id')
            ->orderBy('total_canjeos', 'desc')
            ->limit(10)
            ->get();

        // Canjeos por día (últimos 30 días)
        $canjeosPorDia = Registro::select(
                DB::raw('DATE(fecha_canjeo) as fecha'),
                DB::raw('COUNT(*) as total_canjeos'),
                DB::raw('SUM(puntos_totales) as total_puntos')
            )
            ->where('fecha_canjeo', '>=', now()->subDays(30))
            ->where('estado', '!=', 'cancelado')
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('administrador.canjeos.estadisticas', compact(
            'productosPopulares',
            'canjeosPorDia'
        ));
    }
}
