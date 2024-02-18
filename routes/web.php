<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ManifestacoesController;

Route::get('/', [HomeController::class, 'index']);


Route::post('/Home', [LoginController::class, 'realizar_Login']);
Route::get('/Home', [HomeController::class, 'homepubli'])->name('usuario.telainicial');


Route::get('/Publicacao/SolicitarResgate', [HomeController::class, 'SolicitarResgate']);

Route::get('/Publicacao/criar_alterar-publicacao', [HomeController::class, 'CadastrarPubli']);

Route::post('/Publicacao/criar_alterar-publicacao', [HomeController::class, 'NewPubli'])->name('publicacoes.store');

Route::get('/Publicacao/criar_alterar-publicacao/{ID}', [HomeController::class, 'EditarPubli'])->name('publicacoes.edit');
Route::put('/Publicacao/criar_alterar-publicacao/{ID}', [HomeController::class, 'update'])->name('publicacoes.update');


Route::delete('/Publicacao/PubliPendentesUserComum/{ID}', [HomeController::class, 'destroy'])->name('publicacoes.delete');

Route::get('/Publicacao/PubliPendentesUserComum', [HomeController::class, 'Publi_Pendente']);
Route::get('/Publicacao/PublicacoesPendentes', [HomeController::class, 'Publi_PendenteADM']);

Route::get('/Publicacao/NaoAceitas', [HomeController::class, 'NaoAceitas']);
Route::get('/Publicacao/NaoAceitasaADM', [HomeController::class, 'NaoAceitasaADM']);

Route::get('/Publicacao/VisualizarSolicitacao', [HomeController::class, 'VisualizarSolicitacao']);
Route::get('/Publicacao/Visualizar', [HomeController::class, 'VisualizarResgate']);

Route::get('/Publicacao/ManifestacoesPublicacoes', [HomeController::class, 'ManifestacoesPublicacoes']);
Route::get('/Publicacao/Manifestadas', [HomeController::class, 'Manifestadas'])->name('manifestacoes.manifestadas');

Route::get('/Publicacao/Consultar', [HomeController::class, 'Consultar']);

Route::get('/Usuario/CadastrarUser', [HomeController::class, 'cadastro']);
Route::get('/Usuario/VisualizarUser', [HomeController::class, 'listar_user']);
Route::get('/Usuario/alteraruser', [HomeController::class, 'AlterarUser_Engrenagem']);
Route::get('/Usuario/alteraruserADM', [HomeController::class, 'AlterarUserADM']);

Route::get('/Usuario', [HomeController::class, 'store']);
Route::post('/Usuario', [LoginController::class, 'store']);  
Route::post('/Publicacao', [HomeController::class, 'cadastrar_resgate']);

Route::put('/usuario/alteraruser/{ID}', [UsuarioController::class, 'update'])->name('usuario.update');


Route::get('/usuario/edit/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');

Route::match(['get', 'put'], '/Publicacao/PublicacoesPendentes', [HomeController::class, 'Publi_PendenteADM'])->name('publicacoes.pendentes');
Route::put('/Publicacao/PublicacoesPendentes/atualizar-status/{ID}/{acaoValue?}', [HomeController::class, 'atualizarStatus_naoaceitasADM'])->name('atualizar.status');

Route::get('/Usuario/alteraruserADM/{ID}', [UsuarioController::class, 'editADM'])->name('usuario.editADM');
Route::put('/Usuario/alteraruserADM/{ID}', [UsuarioController::class, 'updateADM'])->name('usuario.updateADM');

//Manifestar
Route::put('/manifestar-publicacao/{id}', [ManifestacoesController::class, 'manifestarPublicacao'])->name('manifestar.publicacao');


//Route::get('/', [HomeController::class, 'NaoAceitasADM'])->name('publicacoes.naoaceitasADM');
//Route::get('/usuario/edit/{id}', [UsuarioController::class, 'editADM'])->name('usuario.editADM');


//Route::get('/welcome', '\App\Http\Controllers\HomeController@welcome')->name('welcome');

//Route::get('/login', 'LoginController@showLoginForm')->name('login');

//Route::get('/', function () {
  // return view('login');
//});
