@extends('layouts.main')

@section('title', 'UnilocalItem')

@section('CorpoPagina')

    @section('TituloPagina')


<link rel="stylesheet" href="/css/style.css">

<div class="container pt-4 " style="margin-left: 280px;">
    <div class="mb-3 mt-5">
        <h3>Manifestações de Publicações</h3>
    </div>
    <br>
    @endsection
    <div class="row justify-content-center">
        <div class="col-12">
            <form id="meuForm"  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @forelse ($items as $item)
                <div class="card">
                        <div class="dropdown">
                            &nbsp;
                          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            &nbsp;
                            <a>{{ $item->UserName }}</a>
                            &nbsp;
                            <a>{{ $item->DATAHORA }}</a>
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
                            <p class="card-text text-secondary mb-2">Resgatado por: Eliene Ribeiro 12/02/2023 00:00:00</p>
                            <p class="card-text text-secondary mb-2">Devolvido por: ADM 14/02/2023 00:00:00</p>

                            @if (session('user_tipousuario') == 0)
                            <div class="actions">
                                <button class="btn btn-primary" style="background-color: rgba(233, 119, 14, 1); outline: none;" type="button" onclick="confirmManifestation({{ $item->ID }})" >Manifestar</button>

                            </div>
                            @endif
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
            <script>
                function confirmManifestation(id) {

                    if (!confirm('Deseja realmente manifestar essa publicação?')) {
                        return false; // Retorna false para cancelar o envio do formulário
                    }
                    
                    // Obtém o formulário
                    var form = document.getElementById('meuForm');
                
                    // Define o atributo "action" do formulário com a URL desejada, incluindo o valor de 'acao' e 'id'
                    form.action = `{{ route('manifestar.publicacao', ['id' => '/']) }}/${id}`;
        
                    // Submete o formulário
                    form.submit();
        
                    return true; // Retorna true para continuar o envio do formulário
                }
            </script>
        </div>
    </div>

    
</div>
@endsection