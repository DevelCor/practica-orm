<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Movimiento;

class InventarioController extends Controller
{
    // Agregar producto
    public function agregarProducto(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0'
        ]);

        $producto = new Producto();
        $producto->nombre = $validated['nombre'];
        $producto->cantidad = $validated['cantidad'];
        $producto->ultimo_movimiento = now();
        $producto->save();

        return response()->json(['message' => 'Producto agregado exitosamente', 'producto' => $producto], 201);
    }

    // Agregar movimiento de entrada (normal)
    public function agregarMovimientoEntrada(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::find($validated['producto_id']);
        $producto->cantidad += $validated['cantidad'];
        $producto->ultimo_movimiento = now();
        $producto->save();

        $movimiento = new Movimiento();
        $movimiento->producto_id = $validated['producto_id'];
        $movimiento->fecha = now()->format('Y-m-d');
        $movimiento->hora = now()->format('H:i:s');
        $movimiento->cantidad = $validated['cantidad'];
        $movimiento->tipo = 'entrada';
        $movimiento->save();

        return response()->json(['message' => 'Movimiento de entrada agregado exitosamente', 'movimiento' => $movimiento], 201);
    }

    // Agregar movimiento de entrada (con SQL nativo)
    public function agregarMovimientoEntradaSQL(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        DB::transaction(function() use ($validated) {
            DB::update('UPDATE productos SET cantidad = cantidad + ?, ultimo_movimiento = ? WHERE id = ?', [
                $validated['cantidad'],
                now(),
                $validated['producto_id']
            ]);

            DB::insert('INSERT INTO movimientos (producto_id, fecha, hora, cantidad, tipo) VALUES (?, ?, ?, ?, ?)', [
                $validated['producto_id'],
                now()->format('Y-m-d'),
                now()->format('H:i:s'),
                $validated['cantidad'],
                'entrada'
            ]);
        });

        return response()->json(['message' => 'Movimiento de entrada (SQL) agregado exitosamente'], 201);
    }

    // Agregar movimiento de salida (normal)
    public function agregarMovimientoSalida(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::find($validated['producto_id']);
        if ($producto->cantidad < $validated['cantidad']) {
            return response()->json(['error' => 'Cantidad insuficiente en el inventario'], 400);
        }

        $producto->cantidad -= $validated['cantidad'];
        $producto->ultimo_movimiento = now();
        $producto->save();

        $movimiento = new Movimiento();
        $movimiento->producto_id = $validated['producto_id'];
        $movimiento->fecha = now()->format('Y-m-d');
        $movimiento->hora = now()->format('H:i:s');
        $movimiento->cantidad = $validated['cantidad'];
        $movimiento->tipo = 'salida';
        $movimiento->save();

        return response()->json(['message' => 'Movimiento de salida agregado exitosamente', 'movimiento' => $movimiento], 201);
    }

    // Agregar movimiento de salida (con SQL nativo)
    public function agregarMovimientoSalidaSQL(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        DB::transaction(function() use ($validated) {
            $producto = DB::select('SELECT cantidad FROM productos WHERE id = ?', [$validated['producto_id']]);
            if ($producto[0]->cantidad < $validated['cantidad']) {
                throw new \Exception('Cantidad insuficiente en el inventario');
            }

            DB::update('UPDATE productos SET cantidad = cantidad - ?, ultimo_movimiento = ? WHERE id = ?', [
                $validated['cantidad'],
                now(),
                $validated['producto_id']
            ]);

            DB::insert('INSERT INTO movimientos (producto_id, fecha, hora, cantidad, tipo) VALUES (?, ?, ?, ?, ?)', [
                $validated['producto_id'],
                now()->format('Y-m-d'),
                now()->format('H:i:s'),
                $validated['cantidad'],
                'salida'
            ]);
        });

        return response()->json(['message' => 'Movimiento de salida (SQL) agregado exitosamente'], 201);
    }

    // Consultar producto
    public function consultarProducto($id)
    {
        $producto = Producto::with('movimientos')->find($id);

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        return response()->json(['producto' => $producto], 200);
    }
}
