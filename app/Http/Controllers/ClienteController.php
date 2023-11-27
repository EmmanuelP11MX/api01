<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Responses\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClienteController extends Controller
{
    public function index()
    {
        try {
            $clientes = Cliente::all();
            return ApiResponse::success('Lista de clientes', 200, $clientes);
        } catch (Exception $e) {
            return ApiResponse::error('Error al obtener los clientes' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'apellidos' => 'required'
            ]);
            $cliente = Cliente::create($request->all());

            return ApiResponse::success('Cliente creado', 201, $cliente);
        } catch (ValidationException $e) {
            return ApiResponse::error('Error al crear el cliente' . $e->getMessage(), 422);
        }
    }

    public function show($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return ApiResponse::success('Cliente encontrado', 200, $cliente);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al obtener el cliente' . $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $request->validate([
                'nombre' => 'required',
                'apellidos' => 'required',
                'nombre' => ['required', Rule::unique('clientes')->ignore($cliente->id)]
            ]);
            $cliente->update($request->all());
            return ApiResponse::success('Cliente actualizado', 200, $cliente);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al actualizar el cliente' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error al actualizar el cliente' . $e->getMessage(), 422);
        }
    }

    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            return ApiResponse::success('Cliente eliminado', 200, $cliente);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error al eliminar el cliente' . $e->getMessage(), 404);
        }
    }

    // Puedes mantener esta función si los productos relacionados a un cliente son necesarios.
    public function productosPorCliente($id)
    {
        $cliente = Cliente::find($id);
        return $cliente->productos;
    }
}
