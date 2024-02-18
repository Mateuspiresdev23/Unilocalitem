@extends('layouts.main')

@section('title', 'UnilocalItem')

@section('CorpoPagina')

    @section('TituloPagina')


<link rel="stylesheet" href="/css/style.css">

<div class="container pt-4 " style="margin-left: 280px;">
    <div class="mb-3 mt-5">
        <h3>Publicações Em Abertas</h3>
    </div>
    <br>
    @endsection

    @if (session('message'))
        <div class='alert alert-success w-100'>
            {{ session('message') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form enctype="multipart/form-data">

                @forelse ($items as $item)

                <div class="card">
                    <div class="container-fluid d-grid gap-3 align-items-center" style="grid-template-columns: 1fr 2fr;">
                        <div class="dropdown pt-2">
                            &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            &nbsp;
                            <a>{{ $item->UserName }}</a>
                            &nbsp;
                            <a>{{ $item->DATAHORA }}</a>
                            <form class="w-100 me-3"></form>  
                        </div>

                    </div>

                       
                    <div class="row g-0">
                        
                        <div class="col-md-4">
                            @if ($item->image)
                            <img src="{{ asset('img/events/'.$item->image) }}" height="250" width="300">
                            @else
                            <img src="{{ asset('img/no-image-found.png') }}" height="250" width="300">
                            @endif
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title mb-2">{{ $item->NOME }}</h5><br>
                            <p class="card-text text-secondary mb-2">{{ $item->DESCRICAO }}</p><br>
                            <p class="card-text text-secondary mb-2">Categoria:  {{ $item->CategoriaName }}</p>
                            <p class="card-text text-secondary mb-2">Horário Cadastrado: {{ $item->DATAHORA }}</p>
                            <p class="card-text text-secondary mb-2">Localizado:  {{ $item->BlocoName }}</p>

                            <div class="actions">
                                <a href="/Publicacao/SolicitarResgate" class="btn btn-primary" style="background-color: rgba(8, 135, 192, 1); outline: none;">Solicitar Resgate</a>
                            </div>

                        </div>
                        </div>
                    </div>
                </div>

                @empty
                    <div class="card text-center p-3 d-flex align-items-center">
                        <img src="{{ asset('img/sempubli.jpg')}}" alt="empty-state" width="450"/>

                        <h4> Nenhuma Publicação encontrada!! </h4>
                    </div>
                @endforelse

            </form>

        </div>
    </div>
    @if (session('user_tipousuario') == 0)
    <div>
        <a class="icon" href="/Publicacao/criar_alterar-publicacao" style="position: fixed; bottom: 20px; right: 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
            </svg>
        </a>
    </div>
    @endif
    
</div>
@endsection