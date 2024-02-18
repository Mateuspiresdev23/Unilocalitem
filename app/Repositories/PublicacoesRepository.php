<?php

namespace App\Repositories;

use App\Models\Publicacoes;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Utils\Filters\LowerThanFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;

class PublicacoesRepository
{
    /**
     *
     * @var Publicacoes
     */
    private $model;

    /**
     *
     * @param Publicacoes $model
     */
    public function __construct(Publicacoes $model)
    {
        $this->model = $model;
    }

    // public function paginate(int $perPage): LengthAwarePaginator {
    //     $vehicles = QueryBuilder::for(Vehicle::class)
    //         ->allowedFilters([
    //             'name',
    //             'brand',
    //             AllowedFilter::custom('vehicle_year', new LowerThanFilter),
    //             AllowedFilter::custom('kilometers', new LowerThanFilter),
    //             AllowedFilter::custom('price', new LowerThanFilter),
    //             'city',
    //             'type',
    //         ])
    //         ->paginate($perPage);

    //     return $vehicles;
    // }

    
     public function paginatePorUser(int $perPage, int $status, int $idusuario): LengthAwarePaginator {
         $publicacoes = QueryBuilder::for(Publicacoes::class)
             ->where('STATUS', $status)
             ->where('IDUSUARIO', $idusuario)
             ->paginate($perPage);

        // Obter informações das imagens associadas a cada publicação
        foreach ($publicacoes as $publicacao) {
            $imageId = DB::table('imagens_objetos')->where('IDOBJETO_PUBLICACOES', $publicacao->ID)->value('IDIMAGEM');
            $imageName = $this->getColumnValueById('imagens','NOME',$imageId);
            $UserName = $this->getColumnValueById('usuarios','NOME',$publicacao->IDUSUARIO);
            $CategoriaName = $this->getColumnValueById('categorias','NOME',$publicacao->IDCATEGORIA);
            $BlocoName = $this->getColumnValueById('blocos','NOME',$publicacao->IDBLOCO);

            $publicacao->image = $imageName;
            $publicacao->UserName = $UserName;
            $publicacao->CategoriaName = $CategoriaName;
            $publicacao->BlocoName = $BlocoName;
        }

        return $publicacoes;
    }

    public function paginateExcluindoUser(int $perPage, int $status, int $idusuario): LengthAwarePaginator {
        $publicacoes = QueryBuilder::for(Publicacoes::class)
            ->where('STATUS', $status)
            ->where('IDUSUARIO','!=', $idusuario)
            ->paginate($perPage);

       // Obter informações das imagens associadas a cada publicação
       foreach ($publicacoes as $publicacao) {
           $imageId = DB::table('imagens_objetos')->where('IDOBJETO_PUBLICACOES', $publicacao->ID)->value('IDIMAGEM');
           $imageName = $this->getColumnValueById('imagens','NOME',$imageId);
           $UserName = $this->getColumnValueById('usuarios','NOME',$publicacao->IDUSUARIO);
           $CategoriaName = $this->getColumnValueById('categorias','NOME',$publicacao->IDCATEGORIA);
           $BlocoName = $this->getColumnValueById('blocos','NOME',$publicacao->IDBLOCO);

           $publicacao->image = $imageName;
           $publicacao->UserName = $UserName;
           $publicacao->CategoriaName = $CategoriaName;
           $publicacao->BlocoName = $BlocoName;
       }

       return $publicacoes;
   }

     public function paginateTodasPubli(int $perPage, int $status): LengthAwarePaginator {
        $publicacoes = QueryBuilder::for(Publicacoes::class)
        ->where('STATUS', $status)
        ->paginate($perPage);

        // Obter informações das imagens associadas a cada publicação
        foreach ($publicacoes as $publicacao) {
            $imageId = DB::table('imagens_objetos')->where('IDOBJETO_PUBLICACOES', $publicacao->ID)->value('IDIMAGEM');
            $imageName = $this->getColumnValueById('imagens','NOME',$imageId);
            $UserName = $this->getColumnValueById('usuarios','NOME',$publicacao->IDUSUARIO);
            $CategoriaName = $this->getColumnValueById('categorias','NOME',$publicacao->IDCATEGORIA);
            $BlocoName = $this->getColumnValueById('blocos','NOME',$publicacao->IDBLOCO);

            $publicacao->image = $imageName;
            $publicacao->UserName = $UserName;
            $publicacao->CategoriaName = $CategoriaName;
            $publicacao->BlocoName = $BlocoName;
        }

        return $publicacoes;
    }

    // Método para obter o valor de uma coluna pelo ID da tabela
    public function getColumnValueById($tableName, $column, $id)
    {
        return DB::table($tableName)->where('ID', $id)->value($column);
    }

    /**
     *
     * @param integer $id
     * @return Publicacoes
     */
    public function findById(int $id): Publicacoes {
        $publicacoes = QueryBuilder::for(Publicacoes::class)
            ->where('ID', $id)
            ->first();

        // Obter informações das imagens associadas a cada publicação
        $imageId = DB::table('imagens_objetos')->where('IDOBJETO_PUBLICACOES', $publicacoes->ID)->value('IDIMAGEM');
        $imageName = $this->getImageNameById($imageId);
        $publicacoes->imageName = $imageName;

        return $publicacoes;
    }

    /**
     * Criar um recurso
     *
     * @param array $data
     * @return Publicacoes
     */
    public function store(array $data): Publicacoes {
        return $this->model->create($data);
    }

    /**
     *
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool {
        $publicacoes = $this->findById($id);

        if(!$publicacoes) {
            return false;
        }

        //retirar o nome da imagem que tava mostrando na tela do edit para não dar erro ao salvar.
        unset($publicacoes->imageName);

        return $publicacoes->update($data);
    }

    /**
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool {
        $publicacoes = $this->findById($id);

        if(!$publicacoes) {
            return false;
        }

        // Deleta os registros da tabela "imagens_objetos" associados à publicação e com a trigger deleta da tabela "imagens" tbm
        $this->deletePublicacaoImages($id);

        // Deleta a publicação
        return $publicacoes->delete();
    }

    /**
     * Deleta os registros da tabela "imagens_objetos" associados à publicação.
     *
     * @param integer $id
     * @return void
     */
    public function deletePublicacaoImages(int $id): void
    {
        DB::table('imagens_objetos')->where('IDOBJETO_PUBLICACOES', $id)->delete();
    }

    public function alterStatusPublicacao(int $id, int $status): void
    {
        DB::table('publicacoes')->where('ID', $id)->update(['STATUS' => $status]);
    }
}