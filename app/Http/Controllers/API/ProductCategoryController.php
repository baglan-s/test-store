<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\EntityNotFoundException;
use App\Http\Resources\ProductCategoryResource;
use App\Repositories\ProductCategoryRepository;
use App\Http\Requests\ProductCategoryCreateRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;

class ProductCategoryController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\ProductCategoryRepository $repository
     */
    public function __construct(
        private ProductCategoryRepository $repository
    ) {}

    /**
     * Get product category list
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return ProductCategoryResource::collection(
            $this->repository->model()
                ->whereNull('parent_id')
                ->with([
                    'children',
                    'children.children',
                ])
                ->withCount(['products'])
                ->paginate($request->limit ?? 20)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param  ProductCategoryCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProductCategoryCreateRequest $request): JsonResource
    {
        try {
            $category = $this->repository->create($request->validated());

            return new ProductCategoryResource($category);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResource
    {
        try {
            if (!$category = $this->repository->getById($id)) {
                return new ErrorResource(new EntityNotFoundException('Category not found!'));
            }

            return new ProductCategoryResource($category);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  ProductCategoryUpdateRequest  $request
     * @param int  $id
     * @return JsonResource
     */
    public function update(ProductCategoryUpdateRequest $request, int $id): JsonResource
    {
        try {
            $category = $this->repository->update($id, $request->validated());

            return new ProductCategoryResource($category);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int  $id
     * @return JsonResponse|JsonResource
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
