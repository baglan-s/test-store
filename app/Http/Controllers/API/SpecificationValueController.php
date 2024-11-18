<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\SpecificationValueRepository;
use App\Http\Resources\SpecificationValueResource;
use App\Http\Requests\SpecificationValueCreateRequest;
use App\Http\Requests\SpecificationValueUpdateRequest;

class SpecificationValueController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\SpecificationValueRepository $repository
     */
    public function __construct(
        private SpecificationValueRepository $repository
    ) {}

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return SpecificationValueResource::collection(
            $this->repository->model()
                ->with('specification')
                ->paginate($request->limit ?? 20)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param SpecificationValueCreateRequest $request
     * @return JsonResource
     */
    public function store(SpecificationValueCreateRequest $request): JsonResource
    {
        try {
            $specificationValue = $this->repository->create($request->validated());

            return new SpecificationValueResource($specificationValue);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResource
     */
    public function show(int $id): JsonResource
    {
        try {
            if (!$specificationValue = $this->repository->getById($id)) {
                return new ErrorResource(new EntityNotFoundException('Specification value not found!'));
            }

            return new SpecificationValueResource($specificationValue);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @param SpecificationValueUpdateRequest $request
     * @return JsonResource
     */
    public function update(SpecificationValueUpdateRequest $request, int $id): JsonResource
    {
        try {
            $specificationValue = $this->repository->update($id, $request->validated());

            return new SpecificationValueResource($specificationValue);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResource|JsonResponse
     */
    public function destroy(int $id): JsonResource|JsonResponse
    {
        try {
            $this->repository->delete($id);

            return response()->json([]);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }
}
