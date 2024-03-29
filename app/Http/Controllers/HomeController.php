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
use App\Repositories\UsuarioRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUsuariosRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    const USERADM = 1;
    const USERCOMUM = 0;

    const PUBLI_PENDENTES = 1;
    const PUBLI_NAOACEITA = 2;
    const PUBLI_EMABERTO = 3;
    const PUBLI_RESGTANDMT= 4; 
    const PUBLI_RESGTCONCLU = 5; // status do manifestar
    const PUBLI_MANIFESTADA = 6;
    const PUBLI_MANIFESTADACONCLUIDA = 7;

    const IMAGENS_PUBLI = 1;
    const IMAGENS_RESGATE = 2;
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
    
    public function index(){

        if (Auth::check()) {
            Auth::logout();
        }
    
        return view('/Home/login');
    }

    public function homepubli(Request $request)
    {

        $perPage = 10;
		$items = $this->publicacoesRepository->paginateTodasPubli($perPage,self::PUBLI_EMABERTO);

        return view('/welcome', compact('items'));
    }



    public function Publi_Pendente(){


		$perPage = 10;
		$items = $this->publicacoesRepository->paginatePorUser($perPage,self::PUBLI_PENDENTES,session('user_id'));
        $userType = session('user_tipousuario');


        if( $userType == self::USERADM){
            return view('/Publicacao/PubliPendentes', compact('items'));
        }
        else{
            return view('/Publicacao/PubliPendentesUserComum', compact('items'));

        }


	}

    public function Publi_PendenteADM(){


		$perPage = 10;
		$items = $this->publicacoesRepository->paginateTodasPubli($perPage,self::PUBLI_PENDENTES);

        return view('/Publicacao/PublicacoesPendentes', compact('items'));
	}

    public function cadastro(){
        return view('/Usuario/CadastrarUser');
    }

    public function listar_user(){

        $usuarios = Usuario::all();


        return view('/Usuario/VisualizarUser', ['usuarios' => $usuarios]);
    }

    public function AlterarUser_Engrenagem(){
        return view('/Usuario/alteraruser');
    }
    public function AlterarUserADM(){
        return view('/Usuario/alteraruserADM');
    }

    public function NaoAceitas(){

        $perPage = 10;

        if (session('user_tipousuario') == self::USERADM) {
            $items = $this->publicacoesRepository->paginateTodasPubli($perPage,self::PUBLI_NAOACEITA);
        } else {
            $items = $this->publicacoesRepository->paginatePorUser($perPage,self::PUBLI_NAOACEITA,session('user_id'));
        }

        return view('/Publicacao/NaoAceitas', compact('items'));
    }
    public function VisualizarSolicitacao(){
        return view('/Publicacao/VisualizarSolicitacao');
    }

    public function VisualizarResgate(){
        return view('/Publicacao/Visualizar');
    }
    public function ManifestacoesPublicacoes(){

		$perPage = 10;

        if (session('user_tipousuario') == self::USERADM) {
            $items = $this->publicacoesRepository->paginateTodasPubli($perPage,self::PUBLI_RESGTCONCLU);
        } else {
            $items = $this->publicacoesRepository->paginateExcluindoUser($perPage,self::PUBLI_RESGTCONCLU,session('user_id'));
        }

        return view('/Publicacao/ManifestacoesPublicacoes', compact('items'));
    }

    public function Manifestadas(){

        $perPage = 10;

        if (session('user_tipousuario') == self::USERADM) {
            $items = $this->publicacoesRepository->paginateTodasPubli($perPage,self::PUBLI_RESGTCONCLU);
            return view('/Publicacao/Manifestadas', compact('items'));
        } else {
            $items = $this->publicacoesRepository->paginatePorUser($perPage,self::PUBLI_RESGTCONCLU,session('user_id'));
            return view('/Publicacao/ManifestadasUserComum', compact('items'));
        }
    }

    public function Consultar(){
        return view('/Publicacao/Consultar');
    }

    public function SolicitarResgate(){
        return view('/Publicacao/SolicitarResgate');
    }

    
    public function CadastrarPubli(Request $request){

        $categorias_combobox = Categoria::pluck('NOME', 'ID');
        $blocos_combobox = Bloco::pluck('NOME', 'ID');


        return view('/Publicacao/criar_alterar-publicacao',compact('categorias_combobox'),compact('blocos_combobox'));
    }


    //CADASTRO NOVO USUÁRIO - TELA LOGIN    

    
    

    public function NewPubli(PublicacoesRequest $request)
    {
       
        $imagens = new Imagens;

        $data = $request->validated();
        
        // Salvar a imagem na tabela imagens
        $Imagens_ID = $this->NewImagems($imagens, $request);

        // Salvar a publicação na tabela publicacoes
        $insert = $this->publicacoesRepository->store($data);
    
        // Verificar se a publicação foi salva corretamente
        if (!$insert) {
            return redirect()->back()->with('errorC', 'Erro ao enviar a publicação...');
        }
    
        // Salvar o registro na tabela imagens_objeto
        $this->saveImagemObjeto($Imagens_ID, $insert->ID, self::IMAGENS_PUBLI);

        $message = 'Publicação enviada com sucesso!';

        if (session('user_tipousuario') == self::USERADM) {
            return $this->redirectPublicacoesPendentesAdmin($message);
        } else {
            return $this->redirectPublicacoesPendentesUserComum($message);
        }
    }

    // Função para redirecionamento de publicações pendentes para o administrador
    private function redirectPublicacoesPendentesAdmin($message)
    {
        return redirect('/Publicacao/PublicacoesPendentes')->with('message', $message);
    }

    // Função para redirecionamento de publicações pendentes para o usuário comum
    private function redirectPublicacoesPendentesUserComum($message)
    {
        return redirect('/Publicacao/PubliPendentesUserComum')->with('message', $message);
    }

    public function NewImagems(Imagens $imagens,Request $request)
    {
        if($request->hasFile('imagem') && $request->file('imagem')->isValid()){

            $requestImagem = $request->imagem;
            $filename = date('YmdHi') . $requestImagem->getClientOriginalName();

            $path = public_path('img/events');
            $requestImagem->move($path, $filename);

            $imagens->nome =  $filename;
            $imagens->caminho =  $path;
            $imagens->extensao =  $requestImagem->getClientOriginalExtension();

            // Salva a imagem no banco de dados
            $imagens->save();

            // Retorna o ID da imagem recém-salva
            return $imagens->id;

        }else
        {
            // Verificar se a imagem foi salva corretamente
            return redirect()->back()->with('errorC', 'Erro ao salvar a imagem...');
        }
    }

    private function saveImagemObjeto($idImagem, $idObjeto, $tipo)
    {   // Tipo 1  - Imagens Publicacoes
        // Tipo 2 - Imagens Resgate.
        
        $imagensObjeto = new ImagensObjetos;
        $imagensObjeto->IDIMAGEM = $idImagem;

        if($tipo == self::IMAGENS_PUBLI){
            $imagensObjeto->IDOBJETO_PUBLICACOES = $idObjeto;
        }else{
            $imagensObjeto->IDOBJETO_RESGATE = $idObjeto;
        }
    
        $imagensObjeto->TIPO = $tipo; // Defina o valor correto para o tipo de objeto, se necessário
        $imagensObjeto->save();
    }

    public function deleteImage($imageName, int $id)
    {
        // Excluindo a imagem física do servidor
        $imagePath = public_path('img/events/' . $imageName);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Deleta os registros da tabela "imagens_objetos" associados à publicação e com a trigger deleta da tabela "imagens" tbm
        $this->publicacoesRepository->deletePublicacaoImages($id);
    }

    public function EditarPubli(int $id): View
    {
        $item = $this->publicacoesRepository->findById($id);
    
        $categorias_combobox = Categoria::pluck('NOME', 'ID');
        $blocos_combobox = Bloco::pluck('NOME', 'ID');
        
        // Busca os dados da imagem relacionada com a publicação (caso exista)
        //$imagem = DB::table('imagens')->where('publicacao_id', $item->id)->first();
       // return view('/Publicacao/criar_alterar-publicacao', compact('item', 'categorias_combobox', 'blocos_combobox', 'imagem'));

        return view('/Publicacao/criar_alterar-publicacao', compact('item', 'categorias_combobox', 'blocos_combobox'));
    }

    public function update(PublicacoesRequest $request, int $id): RedirectResponse
    {
        $imagens = new Imagens;
        //$request->merge(['STATUS' => 1]); // Define um valor padrão (1 neste exemplo) para o campo status
        $data = $request->validated();
        
        // Verificar se o formulário inclui uma nova imagem
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            // Buscar o nome da imagem antiga associada à publicação
            $publicacao = $this->publicacoesRepository->findById($id);
            if ($publicacao->imageName) {
                $this->deleteImage($publicacao->imageName, $id); // Excluir a imagem antiga
            }

            // Salvar a nova imagem na tabela imagens
            $Imagens_ID = $this->NewImagems($imagens, $request);
    
            // Salvar o registro na tabela imagens_objeto
            $this->saveImagemObjeto($Imagens_ID, $id, self::IMAGENS_PUBLI);
        }
    
        // Atualizar os campos da publicação usando o repositório
        $update = $this->publicacoesRepository->update($id, $data);

        if (!$update) {
            return redirect()->back()->with('errorC', 'Erro ao atualizar a publicação...');
        }
        
        $message = 'Publicação atualizada com sucesso!';

        if (session('user_tipousuario') == self::USERADM) {
            return $this->redirectPublicacoesPendentesAdmin($message);
        } else {
            return $this->redirectPublicacoesPendentesUserComum($message);
        }
    }

    public function destroy(int $id): RedirectResponse
	{
		$delete = $this->publicacoesRepository->delete($id);

		if (!$delete) {
			return redirect()->back()->with('error', 'Erro ao excluir publicação');
		}

        $message = 'Publicação excluída com sucesso';

        if (session('user_tipousuario') == self::USERADM) {
            return $this->redirectPublicacoesPendentesAdmin($message);
        } else {
            return $this->redirectPublicacoesPendentesUserComum($message);
        }
	}
    
    public function atualizarStatus_naoaceitasADM(Request $request, $id, $acaoValue = null)
    {
        // Aqui você pode realizar validações e lógica de atualização
        $publicacao = Publicacoes::find($id);

        if (!$publicacao) {
            return back()->with('error', 'Publicação não encontrada');
        }

        if($acaoValue == 1){
            $publicacao->STATUS = self::PUBLI_EMABERTO; 
        }else if($acaoValue == 2){
            $publicacao->STATUS = self::PUBLI_NAOACEITA;
        }

        $publicacao->save();

        $message = 'Status atualizado com sucesso.';

        return $this->redirectPublicacoesPendentesAdmin($message);

    }

    public function cadastrar_resgate(Request $request){

        $resgate = new Resgate;

        $resgate->titulo =  $request->titulo;
        $resgate->descricao =  $request->descricao;
        $resgate->categoria =  "Garrafinha d'água";
        $resgate->bloco=  "IV";
        
        if($request->hasFile('img') && $request->file('img')->isValid()){
            $requestImg = $request->img;

            $extension = $requestImg->extension();

            $ImgName = md5($requestImg->img->getClientOriginalName() . strtotime("now")) . "." . $extension;

        }

        $resgate->save();

        return redirect('/welcome');
    }


}
