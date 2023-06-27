<?php

namespace App\Http\Controllers\Movimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movimientos\Usuario;

class UsersController extends Controller
{
    public function users()
    {
        $usuarios = Usuario::all();
        $clienteCount = $this->getClienteCount();
        $proveedoresCount = $this->getProveedoresCount();

        return view('admin.movimientos.users', compact('usuarios', 'clienteCount', 'proveedoresCount'));
    }

    public function getClienteCount()
    {
        $clienteCount = Usuario::where('tipo_usuario', 'cliente')->count();

        return $clienteCount;
    }

    public function getProveedoresCount()
    {
        $proveedoresCount = Usuario::where('tipo_usuario', 'proveedor')->count();

        return $proveedoresCount;
    }

    public function agregarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:cashflow.usuarios,email',
        ]);

        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->tipo_usuario = $request->tipo_usuario; // Asegúrate de obtener el valor correcto del campo "tipo_usuario"
        $usuario->direccion = $request->direccion;
        $usuario->telefono = $request->telefono;
        $usuario->email = $request->email;
        $usuario->status = 'activo'; // Asegúrate de asignar el valor correcto para el campo "status"
        $usuario->save();

        return redirect()->route('admin.movimientos.users')->with('success', 'Usuario creado exitosamente');
    }
}
