<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ← CORRECCIÓN: Importar la clase Controller correcta
use App\Models\RegistroPuntos;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegistroPuntoController extends Controller // ← Extiende de Controller, no de api\Controller
{
    /**
     * Obtener todos los registros de puntos
     */
    public function index(): JsonResponse
    {
        try {
            $registros = RegistroPuntos::with('usuario')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $registros,
                'count' => $registros->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los registros',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo registro de puntos
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'msg_id' => 'required|uuid|unique:registro_puntos,msg_id',
                'usuario_id' => 'nullable|exists:usuarios,id',
                'numeroRFID' => 'nullable|string|max:8',
                'peso_gramos' => 'required|integer|min:0',
                'puntos_asignados' => 'required|numeric|min:0',
                'leido_en' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $registro = RegistroPuntos::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Registro creado exitosamente',
                'data' => $registro->load('usuario')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un registro específico
     */
    public function show($id): JsonResponse
    {
        try {
            $registro = RegistroPuntos::with('usuario')->find($id);

            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $registro
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un registro
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $registro = RegistroPuntos::find($id);

            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'msg_id' => [
                    'sometimes',
                    'uuid',
                    Rule::unique('registro_puntos', 'msg_id')->ignore($id)
                ],
                'usuario_id' => 'nullable|exists:usuarios,id',
                'numeroRFID' => 'nullable|string|max:8',
                'peso_gramos' => 'sometimes|integer|min:0',
                'puntos_asignados' => 'sometimes|numeric|min:0',
                'leido_en' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $registro->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado exitosamente',
                'data' => $registro->load('usuario')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un registro
     */
    public function destroy($id): JsonResponse
    {
        try {
            $registro = RegistroPuntos::find($id);

            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro no encontrado'
                ], 404);
            }

            $registro->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener registros por usuario
     */
    public function porUsuario($usuarioId): JsonResponse
    {
        try {
            $registros = RegistroPuntos::with('usuario')
                ->where('usuario_id', $usuarioId)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalPuntos = $registros->sum('puntos_asignados');

            return response()->json([
                'success' => true,
                'data' => $registros,
                'total_puntos' => (float) $totalPuntos,
                'count' => $registros->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los registros del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener total de puntos por usuario
     */
    public function totalPuntosUsuario($usuarioId): JsonResponse
    {
        try {
            $totalPuntos = RegistroPuntos::where('usuario_id', $usuarioId)
                ->sum('puntos_asignados');

            return response()->json([
                'success' => true,
                'usuario_id' => $usuarioId,
                'total_puntos' => (float) $totalPuntos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular el total de puntos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
