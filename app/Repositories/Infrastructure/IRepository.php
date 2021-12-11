<?php

namespace App\Repositories\Infrastructure;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IRepository
{
    /**
     * @return Collection
     */
    public function all();

    /**
     * @param $id
     * @return Model
     */
    public function getById($id);

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @param $id
     * @return Model
     */
    public function update(array $attributes, $id);

    /**
     * @param $id
     * @return array
     */
    public function delete($id);

    /**
     * @return array
     */
    public function getFillable(): array;
}
