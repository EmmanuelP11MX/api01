<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Responses\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriaController extends Controller
{
    public function index()
    {
        try {
            $categoria = Categoria::all();
            return ApiResponse::success('Lista de categorias', 200, $categoria);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener las categorias' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|unique:categorias'
            ]);
            $categoria = Categoria::create($request->all());

            return ApiResponse::success('Categoria creada', 201, $categoria);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error al crear la categoria' . $e->getMessage(), 422);
        }
    }

    public function show($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return ApiResponse::success('Categoria encontrada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al obtener la categoria' . $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $request->validate([
                'nombre' => ['required', Rule::unique('Categorias')->ignore($categoria->id)]
            ]);
            $categoria->update($request->all());
            return ApiResponse::success('Categoria actualizada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al actualizar la categoria' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al actualizar la categoria' . $e->getMessage(), 422);
        }
    }

    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Categoria eliminada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al eliminar la categoria' . $e->getMessage(), 404);
        }
    }

    public function productosPorCategoria($id)
    {
        $categoria = Categoria::find($id);
        return $categoria->productos;
    }
}
