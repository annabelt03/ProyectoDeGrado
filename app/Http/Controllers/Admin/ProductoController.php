<?php

namespace App\Http\Controllers\Admin;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();
        
        // Filtro por nombre
        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombreProducto', 'like', '%' . $request->nombre . '%');
        }
        
        // Filtro por puntos (rango)
        if ($request->has('puntos_min') && $request->puntos_min != '') {
            $query->where('puntos_valor', '>=', $request->puntos_min);
        }
        
        if ($request->has('puntos_max') && $request->puntos_max != '') {
            $query->where('puntos_valor', '<=', $request->puntos_max);
        }
        
        // Filtro por stock
        if ($request->has('stock_min') && $request->stock_min != '') {
            $query->where('stock', '>=', $request->stock_min);
        }
        
        // Filtro por estado activo
        if ($request->has('activo') && $request->activo != '') {
            $query->where('activo', $request->activo);
        }
        
        // Ordenamiento
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $productos = $query->orderBy($sortField, $sortDirection)
                          ->paginate(10);
        
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreProducto' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'puntos_valor' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Manejar la carga de imagen
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $data['imagen'] = $imagePath;
        }

        Producto::create($data);

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        $request->validate([
            'nombreProducto' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'puntos_valor' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'activo' => 'sometimes|boolean',
        ]);

        $data = $request->all();
        
        // Manejar la actualización de imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $data['imagen'] = $imagePath;
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        
        $producto->delete();

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto eliminado exitosamente.');
    }

    /**
     * Cambiar estado activo/inactivo del producto
     */
    public function toggleStatus($id)
    {
        $producto = Producto::findOrFail($id);
        
        $producto->update([
            'activo' => !$producto->activo
        ]);

        $status = $producto->activo ? 'activado' : 'desactivado';
        
        return redirect()->route('admin.productos.index')
                         ->with('success', "Producto {$status} exitosamente.");
    }

    /**
     * Mostrar productos con bajo stock
     */
    public function stockBajo(Request $request)
    {
        $umbral = $request->get('umbral', 5);
        
        $productos = Producto::where('stock', '<=', $umbral)
                            ->orderBy('stock', 'asc')
                            ->paginate(10);
        
        return view('productos.stock-bajo', compact('productos', 'umbral'));
    }
}