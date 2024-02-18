<?php

namespace App\Repositories;

use App\Models\Usuario;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UsuarioRepository
{
    /**
     *
     * @var Usuario
     */
    private $model;

    /**
     *
     * @param Usuario $model
     */
    public function __construct(Usuario $model)
    {
        $this->model = $model;
    }

    /**
     *
     * @param integer $id
     * @return Usuario|null
     */
    public function findById(int $id): ?Usuario {
        $usuario = QueryBuilder::for(Usuario::class)
            ->where('ID', $id)
            ->first();

        return $usuario;
    }

    /**
     * Criar um recurso
     *
     * @param array $data
     * @return Usuario
     */
    public function store(array $data): Usuario {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            // Exibir ou registrar o erro para depuração
            dd($e->getMessage());
        }
    }

    /**
     *
     * @param integer $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool {
        $usuario = $this->findById($id);

        if(!$usuario) {
            return false;
        }

        return $usuario->update($data);
    }

    /**
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool {
        $usuario = $this->findById($id);

        if(!$usuario) {
            return false;
        }

        return $usuario->delete();
    }
}