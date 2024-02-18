@extends('layouts.main')

@section('title', 'UnilocalItem')

@section('CorpoPagina')

@section('TituloPagina')

<div class="container pt-4 " style="margin-left: 280px;">
    <div class="mb-3 mt-5">
        <h3>Solicitar Resgate</h3>
    </div>
    <br>
@endsection
    <div class="card">
        <div class="card-body">
            <form method="POST" action="/Publicacao" enctype="multipart/from-data">
                @csrf
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="titulo">Título:</label>
                        <input class="form-control" id="titulo" name="titulo" value="" type= "text" autocomplete="off"/>
                        @error('titulo')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label class="form-label" for="descricao">Descrição:</label>
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
                        <label for="img" class="form-label">Documento Frente e Verso:</label>
                        <input class="form-control" id="img" name="img" type= "file"/>
                        @error('img')
                        <div class="alert alert-danger mt-3">(($message))</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <textarea class="form-control" readonly id="descricao" name="descricao" value="" rows="4">
                        </textarea>

                        <input class="form-check-input" id="tipo" name="tipo" value="" type= "checkbox"/>
                        <label class="form-check-label" for="tipo">Li e estou de acordo com os termos</label>

             
                    </div>
                </div>

                <div class="row d-flex justify-content-between align-items-end">
                            <div class="form-group col-sm-12 col-md-12 mb-3 text-end">
                                <button class="btn btn-primary" style="background-color: rgba(244, 7, 7, 1); outline: none;" >Cancelar</button>
                                &nbsp;
                                <button class="btn btn-primary" style="background: rgba(8, 192, 26, 1); outline: none;" type="submit" >Salvar</button>
                            </div>
                        </div>

                <br><br>


                
            </form>
        </div>
    </div>
</div>
@endsection