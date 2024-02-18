<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bloco;
use App\Models\Categoria;
use App\Models\Publicacoes;
use App\Models\Resgate;
use App\Models\Usuario;
use App\Models\Imagens;
use App\Models\ImagensObjetos;
use App\Http\Requests\PublicacoesRequest;
use App\Repositories\PublicacoesRepository;
use App\Http\Requests\ImagensRequest;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUsuariosRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;



class LoginController extends Controller
{


     /**
     *
     * @var PublicacoesRepository
     */
    private $publicacoesRepository;

    /**
     *
     * @param publicacoesRepository $publicacoesRepository
     */
    public function __construct(PublicacoesRepository $publicacoesRepository )
    {
        $this->publicacoesRepository = $publicacoesRepository;
    }

    public function realizar_Login(Request $request)
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        $password = trim($request->input('password')); // Remover espaços em branco da senha fornecida
    
        $usuario = Usuario::where('cpf', $cpf)->first();
        
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }
       
        if($usuario->status == 1){
            if (Hash::check($password, $usuario->password)) {
                Auth::login($usuario);
    
                session(['user_id' => $usuario->ID]);
                session(['user_name' => $usuario->nome]);
                session(['user_tipousuario' => $usuario->tipousuario]);
                session(['user_status' => $usuario->status]);

                $perPage = 10;
                $items = $this->publicacoesRepository->paginateTodasPubli($perPage,3);

                return view('/welcome', compact('items', 'usuario'));
            } else {
                return redirect()->back()->with('error', 'CPF ou senha inválidos.');
            }
        }
        else{
            return redirect()->back()->with('error', 'Usuário inativo! Consulte o Administrador do Sistema!');
        }
    }

   

    public function store(RegisterUsuariosRequest $request)
    {
        $request->validated();
    
        $usuario = new Usuario;
        $usuario->nome = trim($request->input('nome')); // Remover espaços em branco do nome
        $usuario->email = trim($request->input('email')); // Remover espaços em branco do email
        $usuario->cpf = preg_replace('/[^0-9]/', '', trim($request->input('cpf'))); // Remover espaços em branco do CPF
        $usuario->tipousuario = 0;
        $usuario->password = Hash::make(trim($request->input('password'))); // Remover espaços em branco da senha
    
        $usuario->save();
    
        return Redirect::to('/')->with('msg', 'Usuário Cadastrado com sucesso!');
    }
    
    

}
