@extends('layouts.main')

@section('title', 'UnilocalItem')

@section('CorpoPagina')

@section('TituloPagina')

<div class="container pt-4 " style="margin-left: 280px;">
    <div class="mb-3 mt-5">
        <h3>Visualizar Resgate</h3>
    </div>
    <br>
@endsection
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="nome">Título Publicação:</label>
                        <input class="form-control" id="titulo" name="titulo" value="" type= "text" autocomplete="off"/>
                        @error('titulo')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="email">Descrição:</label>
                        <textarea class="form-control" id="descricao" name="descricao" value="" rows="4"></textarea>
                        @error('descricao')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="categoria" class="form-label">Categoria:</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">Selecione uma opção</option>
                            <option @if((isset($item) && $item->categoria == "Garrafinha d'água") || isset($item) && $item->categoria == old('categoria')) selected 
                                @endif>Garrafinha d'água</option>
                            <option @if((isset($item) && $item->categoria == "Lápis") || isset($item) && $item->categoria == old('categoria')) selected 
                                @endif>Lápis</option>
                        </select>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="bloco" class="form-label">Bloco:</label>
                        <select class="form-select" id="bloco" name="bloco">
                        <option value="">Selecione uma opção</option>
                            <option @if((isset($item) && $item->bloco == "Bloco 4") || isset($item) && $item->bloco == old('bloco')) selected 
                                @endif>Bloco 4</option>
                        </select>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="nome">Nome:</label>
                        <input class="form-control" id="nome" name="nome" value="" type= "text" autocomplete="off"/>
                        @error('nome')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="cpf">CPF:</label>
                        <input class="form-control" id="cpf" name="cpf" value="" type= "text" autocomplete="off"/>
                        @error('cpf')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="img" class="form-label">Imagem:</label>
                        <input class="form-control" id="img" name="img" value="" type= "file"/>
                        @error('img')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>
                <br><br>


                <div class="row d-flex justify-content-between align-items-end">
                    <div class="form-group col-sm-12 col-md-6 mb-3 text-end">

                        <button class="btn btn-primary" type="submit" >Voltar</button>
                    </div>
                </div>
            </form>
</div>
@endsection