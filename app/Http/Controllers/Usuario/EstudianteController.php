<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\RegistroCanjeo;
use App\Models\Usuario;



class EstudianteController extends Controller
{
    /**
     * Mostrar productos disponibles para canjear
     */
    public function productosDisponibles(Request $request)
    {
         $authUser = Auth::user();
    $usuario = Usuario::find($authUser->id);
        // Usar los métodos del modelo
        if (!$usuario || !$usuario->esEstudiante()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $query = Producto::where('activo', true)
                        ->where('stock', '>', 0);

        // Filtro por nombre
        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombreProducto', 'like', '%' . $request->nombre . '%');
        }

        // Usar el método puedeCanjear indirectamente
        if ($request->has('puntos_max') && $request->puntos_max != '') {
            $query->where('puntos_valor', '<=', $request->puntos_max);
        } else {
            $query->where('puntos_valor', '<=', $usuario->puntos);
        }

        $productos = $query->orderBy('puntos_valor', 'asc')
                          ->paginate(12);

        return view('estudiante.productos', compact('productos', 'usuario'));
    }

    /**
     * Procesar el canje de un producto
     */
    public function canjearProducto(Request $request, $id)
    {
        $authUser = Auth::user();
            $usuario = Usuario::find($authUser->id);        
        if (!$usuario || !$usuario->esEstudiante()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $producto = Producto::where('activo', true)
                           ->where('stock', '>', 0)
                           ->findOrFail($id);

        $request->validate([
            'cantidad' => 'required|integer|min:1|max:' . $producto->stock
        ]);

        $cantidad = $request->cantidad;
        $puntosTotales = $producto->puntos_valor * $cantidad;

        // Usar el método del modelo
        if (!$usuario->puedeCanjear($puntosTotales)) {
            return redirect()->back()
                           ->with('error', 'No tienes suficientes puntos para canjear este producto.');
        }

        // Verificar que hay suficiente stock
        if ($producto->stock < $cantidad) {
            return redirect()->back()
                           ->with('error', 'No hay suficiente stock disponible.');
        }

        // Usar el método personalizado del modelo
        try {
            $usuario->decrementarPuntos($puntosTotales);
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', $e->getMessage());
        }

        // Crear el registro de canje
        $registro = RegistroCanjeo::create([
            'usuario_id' => $usuario->id,
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
            'puntos_totales' => $puntosTotales,
            'estado' => 'canjeado',
            'fecha_canjeo' => now(),
        ]);

        // Actualizar stock del producto
        $producto->decrement('stock', $cantidad);

        return redirect()->route('estudiante.mis-canjes')
                         ->with('success', "¡Producto canjeado exitosamente! Se descontaron {$puntosTotales} puntos.");
    }

    /**
     * Mostrar historial de canjes del estudiante
     */
    public function misCanjes(Request $request)
    {
        
         $authUser = Auth::user();
        $usuario = Usuario::find($authUser->id);
        
        if (!$usuario || !$usuario->esEstudiante()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Usar el método canjes() del modelo
        $query = $usuario->canjes();

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $canjes = $query->orderBy('fecha_canjeo', 'desc')
                       ->paginate(10);

        // Usar el método del modelo
        $totalPuntosCanjeados = $usuario->totalPuntosCanjeados();

        return view('estudiante.mis-canjes', compact('canjes', 'totalPuntosCanjeados', 'usuario'));
    }

    /**
     * Dashboard del estudiante
     */
    public function dashboard()
    {
         $authUser = Auth::user();
        $usuario = Usuario::find($authUser->id);
        
        if (!$usuario || !$usuario->esEstudiante()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        // Usar los métodos del modelo
        $estadisticas = [
            'totalCanjes' => $usuario->canjes()->count(),
            'canjesPendientes' => $usuario->canjes()->where('estado', 'canjeado')->count(),
            'canjesEntregados' => $usuario->canjes()->where('estado', 'entregado')->count(),
            'totalPuntosCanjeados' => $usuario->totalPuntosCanjeados(),
        ];

        // Últimos canjes usando el método del modelo
        $ultimosCanjes = $usuario->canjes()
                                ->orderBy('fecha_canjeo', 'desc')
                                ->limit(5)
                                ->get();

        // Productos recomendados
        $productosRecomendados = Producto::where('activo', true)
                                        ->where('stock', '>', 0)
                                        ->where('puntos_valor', '<=', $usuario->puntos)
                                        ->orderBy('puntos_valor', 'asc')
                                        ->limit(4)
                                        ->get();

        return view('estudiante.dashboard', compact('usuario', 'estadisticas', 'ultimosCanjes', 'productosRecomendados'));
    }
}