<?php

namespace App\Repositories;

use App\Models\SpecificationValue;
use App\Repositories\SpecificationRepository;
use App\Exceptions\EntityNotFoundException;

class SpecificationValueRepository
{
    public function __construct(
        private SpecificationRepository $specificationRepository
    ) {}
    public function model(): SpecificationValue
    {
        return new SpecificationValue();
    }

    public function getById(int $id): SpecificationValue | null
    {
        return SpecificationValue::find($id);
    }

    public function create(array $attributes): SpecificationValue
    {
        if (!$specification = $this->specificationRepository->getById($attributes['specification_id'])) {
            throw new EntityNotFoundException('Specification not with id ' . $attributes['specification_id'] . ' found!');
        }

        return $specification->values()->create($attributes);
    }

    public function update(int $id, array $attributes): SpecificationValue
    {
        if (!$specificationValue = $this->getById($id)) {
            throw new EntityNotFoundException('Specification value not found!');
        }

        if (!$this->specificationRepository->getById($attributes['specification_id'])) {
            throw new EntityNotFoundException('Specification with id ' . $attributes['specification_id'] . ' not found!');
        }

        $specificationValue->update($attributes);

        return $this->getById($id);
    }

    public function delete(int $id)
    {
        if (!$specificationValue = $this->getById($id)) {
            throw new EntityNotFoundException('Specification value not found!');
        }

        $specificationValue->delete();
    }
}