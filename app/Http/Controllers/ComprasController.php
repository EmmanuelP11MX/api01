<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compras;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ComprasController extends Controller
{    public function index()
    {
        try {
            $compras = Compras::all();
            return ApiResponse::success('compras realizadas',200, $compras);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener las comprass'.$e->getMessage(), 500);
        }

    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|unique:compras'
            ]);
            $compras = Compras::create($request->all());

            return ApiResponse::success('Compras creada', 201, $compras);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error al crear la compra'.$e->getMessage(), 422);
        }
       
    }

    public function show($id)
    {
        try {
            $compras = Compras::findOrFail($id);
            return ApiResponse::success('Compra encontrada', 200, $compras);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al obtener la compra'.$e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
       try {
            $categoria = Compras::findOrFail($id);
            $categoria->validate([
                'subtotal' => ['required',Rule::unique('compras')->ignore($categoria->id)]
            ]);
            return ApiResponse::success('Compra actualizada', 200, $categoria);
       } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al actualizar la compra'.$e->getMessage(), 404);
       }catch (Exception $e) {
            return ApiResponse::error('Error al actualizar la compra'.$e->getMessage(), 422);
       }
    }

    public function destroy($id)
    {
        try {
            $categoria = Compras::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Compra eliminada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al eliminar la compra'.$e->getMessage(), 404);
        } 
    }

    public function productosPorCategoria($id)
    {
        $categoria = Compras::find($id);
        return $categoria->productos;
    }
    //
}

