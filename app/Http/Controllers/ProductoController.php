<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

class ProductoController extends Controller
{  public function index()
    {
        try {
            $producto = producto::all();
            return ApiResponse::success('producto',200, $producto);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener loss productos'.$e->getMessage(), 500);
        }

    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|unique:producto'
            ]);
            $producto = producto::create($request->all());

            return ApiResponse::success('producto creado', 201, $producto);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error al crear al encontrar el producto'.$e->getMessage(), 422);
        }
       
    }

    public function show($id)
    {
        try {
            $producto = producto::findOrFail($id);
            return ApiResponse::success('Producto encontrado', 200, $producto);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al obtener el producto'.$e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
       try {
            $categoria = producto::findOrFail($id);
            $categoria->validate([
                'subtotal' => ['required',Rule::unique('producto')->ignore($categoria->id)]
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
            $categoria = producto::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Compra eliminada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al eliminar la compra'.$e->getMessage(), 404);
        } 
    }

    public function productosPorCategoria($id)
    {
        $categoria = producto::find($id);
        return $categoria->productos;
    }
    //
}
