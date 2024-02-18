<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function edit(int $id)
    {
        $usuario = Usuario::find($id);
       

        if (!$usuario) {
            return redirect()->route('usuario.telainicial');
        }

        return view('/Usuario/alteraruser', compact('usuario'));
    }


    public function editADM(int $id)
    {
        //dd($id);
        $usuario = Usuario::find($id);
       

        if (!$usuario) {
            return redirect()->route('usuario.telainicial');
        }
        $isAdmin = $usuario->tipousuario == 1;
        $isStatus = $usuario->tipousuario == 0;
        
        return view('/Usuario/alteraruserADM', compact('usuario', 'isAdmin', 'isStatus'));
    }

    public function updateADM(Request $request, $ID)
    {

        $request->validate([
            'nome' => 'required|max:255', 
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);


        $usuario = Usuario::find($ID);

       

        if (!$usuario) {
            return redirect()->route('usuario.telainicial')->with('error', 'Usuário não encontrado'); 
        }

        $usuario->nome = trim($request->input('nome'));
        if ($request->filled('password')) {
            $usuario->password = Hash::make(trim($request->input('password')));
        } 

        $usuario->tipousuario = $request->has('tipousuario') && $request->input('tipousuario') == 1 ? 1 : 0;
        $usuario->status = $request->has('status') && $request->input('status') == 1 ? 1 : 0;

        if ($usuario->save()) {
            return redirect()->route('usuario.telainicial')->with('success', 'Usuário atualizado com sucesso');
        } else {
            return redirect()->route('usuario.telainicial')->with('error', 'Erro ao atualizar o usuário');
        }
    }

    public function update(Request $request, $ID)
    {
        
        $request->validate([
            'nome' => 'required|max:255', 
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $usuario = Usuario::find($ID);

        if (!$usuario) {
            return redirect()->route('usuario.telainicial')->with('error', 'Usuário não encontrado'); 
        }

        $usuario->nome = trim($request->input('nome'));
        if ($request->filled('password')) {
            $usuario->password = Hash::make(trim($request->input('password')));
        } 

        if ($usuario->save()) {
            return redirect()->route('usuario.telainicial')->with('success', 'Usuário atualizado com sucesso');
        } else {
            return redirect()->route('usuario.telainicial')->with('error', 'Erro ao atualizar o usuário');
        }
    }
}
