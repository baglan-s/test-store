<?php

namespace App\Repositories;

use App\Models\Specification;
use App\Exceptions\EntityNotFoundException;

class SpecificationRepository
{
    public function model(): Specification
    {
        return new Specification();
    }

    public function getById(int $id): Specification | null
    {
        return Specification::find($id);
    }

    public function create(array $attributes): Specification
    {
        return Specification::create($attributes);
    }

    public function update(int $id, array $attributes): Specification
    {
        if (!$specification = $this->getById($id)) {
            throw new EntityNotFoundException('Specification not found!');
        }

        $specification->update($attributes);

        return $this->getById($id);
    }

    public function delete(int $id)
    {
        if (!$specification = $this->getById($id)) {
            throw new EntityNotFoundException('Specification not found!');
        }

        $specification->delete();
    }
}