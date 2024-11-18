<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\SpecificationRepository;
use App\Http\Resources\SpecificationResource;
use App\Http\Requests\SpecificationCreateRequest;
use App\Http\Requests\SpecificationUpdateRequest;

class SpecificationController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\SpecificationRepository $repository
     */
    public function __construct(
        private SpecificationRepository $repository
    ) {}

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return SpecificationResource::collection(
            $this->repository->model()
                ->with('values')
                ->paginate($request->limit ?? 20)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param SpecificationCreateRequest $request
     * @return JsonResource
     */
    public function store(SpecificationCreateRequest $request): JsonResource
    {
        try {
            $specification = $this->repository->create($request->validated());

            return new SpecificationResource($specification);
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
            if (!$specification = $this->repository->getById($id)) {
                return new ErrorResource(new EntityNotFoundException('Specification not found!'));
            }

            return new SpecificationResource($specification);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @param SpecificationUpdateRequest $request
     * @return JsonResource
     */
    public function update(SpecificationUpdateRequest $request, int $id): JsonResource
    {
        try {
            $specification = $this->repository->update($id, $request->validated());

            return new SpecificationResource($specification);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResource|JsonResponse
     */
    public function destroy(int $id): JsonResponse|JsonResource
    {
        try {
            $this->repository->delete($id);

            return response()->json([]);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }
}
