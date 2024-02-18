<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bloco;
use App\Models\Categoria;
use App\Models\Publicacoes;
use App\Models\Resgate;
use App\Models\Usuario;
use App\Models\Manifestacoes;
use App\Repositories\PublicacoesRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUsuariosRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;



class ManifestacoesController extends Controller
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
    public function __construct(PublicacoesRepository $publicacoesRepository)
    {
        $this->publicacoesRepository = $publicacoesRepository;

    }

    
    const PUBLI_PENDENTES = 1;
    const PUBLI_NAOACEITA = 2;
    const PUBLI_EMABERTO = 3;
    const PUBLI_RESGTANDMT= 4; 
    const PUBLI_RESGTCONCLU = 5; // status do manifestar
    const PUBLI_MANIFESTADA = 6;
    const PUBLI_MANIFESTADACONCLUIDA = 7;

    public function manifestarPublicacao(Request $request, $id)
    {
        // Realize aqui o processo de manifestação da publicação
        // Por exemplo, você pode obter os dados enviados pelo formulário e salvar no banco de dados
        // $request->input('campo') pode ser usado para obter os valores dos campos enviados

        // Exemplo de salvar o ID da publicação e a data/hora da manifestação
        $publicacao = DB::table('publicacoes')->find($id);
        if ($publicacao) {
                DB::table('manifestacoes')->insert([
                    'DATAHORA' => now(),
                    'IDUSUARIO' =>  session('user_id'),
                    'IDPUBLICACAO' => $id,
            ]);

            $this->publicacoesRepository->alterStatusPublicacao($id,self::PUBLI_MANIFESTADA);

            return redirect()->route('manifestacoes.manifestadas'); // Redirecionar para outra rota após a manifestação
        }

        return redirect()->back()->with('error', 'Publicação não encontrada.'); // Redirecionar de volta à página com uma mensagem de erro, se a publicação não for encontrada
    }

}
