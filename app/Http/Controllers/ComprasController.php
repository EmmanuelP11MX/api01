<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Compras;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Responses\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ComprasController extends Controller
{
    public function index()
    {
        try {
            $compras = Compras::all();
            return ApiResponse::success('compras realizadas', 200, $compras);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener las comprass' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $productos = $request->input('productos');
            if (empty($productos)) {
                return ApiResponse::error('No se proporcionaron productos', 400);
            }

            $validator = Validator::make($request->all(),[
                'productos' => 'required|array',
                'productos.*.productos_id' => 'required|integer|exists:productos,id',
                'productos.*.cantidad' => 'requiered|integer|min:1'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Datos invalidos en la lista de productos',400,$validator->errors());
            }

            $productoIds = array_column($productos, 'producto_id');
            if (count($productoIds) !== count(array_unique($productoIds))) {
                return ApiResponse::error('No se permiten productos duplicados para la compra',400);
            }

            $totalPagar = 0;
            $subtotal = 0;
            $compraItems = [];

            foreach ($productos as $producto) {

                $productoB = Producto::find($producto['producto_id']);
                if (!$productoB) {
                    return ApiResponse::error('Producto no encontrado',404);
                }

                if ($productoB->cantidad_disponible < $producto['cantidad']) {
                    return ApiResponse::error('El Producto no tiene suficiente cantidad disponible',404);
                }

                $productoB->cantidad_disponible = $productoB->cantidad_disponible - $producto['cantidad'];
                $productoB->save();

                $subtotal = $productoB->precio * $producto['cantidad'];
                $totalPagar = $totalPagar + $subtotal;

                $compraItems[] = [
                    'producto_id' => $productoB->id,
                    'precio' => $productoB->precio,
                    'cantidad' => $producto['cantidad'],
                    'subtotal' => $subtotal
                ];
            }

            $compra = Compras::create([
                'subtotal' => $totalPagar,
                'total' => $totalPagar
            ]);

            $compra->productos()->attach($compraItems);

            return ApiResponse::success('Compra realizada con exito',201,$compra);

        } catch (QueryException $e) {
            return ApiResponse::success('Error en la consulta de la BD',500);
        } 
    }

    public function show($id)
    {
        try {
            $compras = Compras::findOrFail($id);
            return ApiResponse::success('Compra encontrada', 200, $compras);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al obtener la compra' . $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $categoria = Compras::findOrFail($id);
            $categoria->validate([
                'subtotal' => ['required', Rule::unique('compras')->ignore($categoria->id)]
            ]);
            return ApiResponse::success('Compra actualizada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al actualizar la compra' . $e->getMessage(), 404);
        }
    }

    public function destroy($id)
    {
        try {
            $categoria = Compras::findOrFail($id);
            $categoria->delete();
            return ApiResponse::success('Compra eliminada', 200, $categoria);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al eliminar la compra' . $e->getMessage(), 404);
        }
    }

    public function productosPorCategoria($id)
    {
        $categoria = Compras::find($id);
        return $categoria->productos;
    }
    //
}