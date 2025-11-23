<?php

namespace App\Http\Controllers\Usuario;


use App\Models\Producto;
use App\Models\RegistroCAnjeo as Registro;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CanjeoController extends Controller
{
    /**
     * Mostrar catálogo de productos para canjeo (usuario)
     */
    public function catalogo()
    {
        $productos = Producto::where('activo', true)
            ->where('stock', '>', 0)
            ->orderBy('puntos_valor')
            ->get();

        $usuario = Auth::user();

        return view('estudainte.canjeo.catalogo', compact('productos', 'usuario'));
    }

    /**
     * Mostrar historial de canjeos del usuario
     */
    public function historial()
    {
        $canjeos = Registro::with('producto')
            ->where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('usuario.canjeo.historial', compact('canjeos'));
    }

    /**
     * Procesar canjeo de producto
     */
    public function canjear(Request $request, $productoId)
    {
        $producto = Producto::where('activo', true)->findOrFail($productoId);
        $usuario = Auth::user();

        // Validar stock
        if ($producto->stock < 1) {
            return redirect()->back()
                ->with('error', 'Producto agotado');
        }

        // Validar puntos suficientes
        if ($usuario->puntos < $producto->puntos_valor) {
            return redirect()->back()
                ->with('error', 'Puntos insuficientes para canjear este producto');
        }

        // Procesar canjeo
        DB::transaction(function () use ($usuario, $producto) {
            // Crear registro de canjeo
            $registro = new Registro();
            $registro->usuario_id = $usuario->id;
            $registro->producto_id = $producto->id;
            $registro->cantidad = 1;
            $registro->puntos_totales = $producto->puntos_valor;
            $registro->estado = 'canjeado';
            $registro->fecha_canjeo = now();
            $registro->save();

            // Restar puntos al usuario
            $usuario->puntos -= $producto->puntos_valor;
            $usuario->save();

            // Reducir stock
            $producto->stock -= 1;
            $producto->save();
        });

        return redirect()->route('estudiante.canjeo.historial')
            ->with('success', 'Producto canjeado exitosamente');
    }

    /**
     * Cancelar canjeo (si está permitido)
     */
    public function cancelar($canjeoId)
    {
        $canjeo = Registro::where('usuario_id', Auth::id())
            ->where('id', $canjeoId)
            ->where('estado', 'canjeado')
            ->firstOrFail();

        // Solo permitir cancelar si fue hace menos de 1 hora
        if ($canjeo->created_at->diffInHours(now()) > 1) {
            return redirect()->back()
                ->with('error', 'Solo puedes cancelar canjeos realizados en la última hora');
        }

        DB::transaction(function () use ($canjeo) {
            $usuario = Auth::user();
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

        return redirect()->route('estudiante.canjeo.historial')
            ->with('success', 'Canjeo cancelado exitosamente');
    }
}
