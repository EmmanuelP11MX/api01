<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\FlareClient\Api;

class CategoriaController extends Controller
{
    public function index()
    {
        try {
            $categoria = Categoria::all();
            return ApiResponse::success('Lista de categorias',200, $categoria);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener las categorias'.$e->getMessage(), 500);
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
            return ApiResponse::error('Error al crear la categoria'.$e->getMessage(), 422);
        }
       
    }

    public function show($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return ApiResponse::success('Categoria encontrada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al obtener la categoria'.$e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
       try {
            $categoria = Categoria::findOrFail($id);
            $categoria->validate([
                'nombre' => ['required',Rule::unique('categorias')->ignore($categoria->id)]
            ]);
            return ApiResponse::success('Categoria actualizada', 200, $categoria);
       } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al actualizar la categoria'.$e->getMessage(), 404);
       }catch (Exception $e) {
            return ApiResponse::error('Error al actualizar la categoria'.$e->getMessage(), 422);
       }
    }

    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Categoria eliminada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al eliminar la categoria'.$e->getMessage(), 404);
        } 
    }

    public function productosPorCategoria($id)
    {
        $categoria = Categoria::find($id);
        return $categoria->productos;
    }
}
