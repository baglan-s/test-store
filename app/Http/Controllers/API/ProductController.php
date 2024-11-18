<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\ProductRepository;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductIndexRequest;

class ProductController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\ProductRepository $repository
     */
    public function __construct(
        private ProductRepository $repository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ProductIndexRequest $request): AnonymousResourceCollection
    {
        return ProductResource::collection(
            $this->repository
                ->filter($request->validated())
                ->paginate($request->limit ?? 10)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param ProductCreateRequest $request
     * @return JsonResource
     */
    public function store(ProductCreateRequest $request): JsonResource
    {
        try {
            $product = $this->repository->create($request->validated());

            return new ProductResource($product);
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
            if (!$product = $this->repository->getById($id)) {
                return new ErrorResource(new EntityNotFoundException('Product not found!'));
            }

            return new ProductResource($product);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Display the specified resource.
     * @param string $slug
     * @return JsonResource
     */
    public function showBySlug(string $slug): JsonResource
    {
        try {
            if (!$product = $this->repository->model()->where('slug', $slug)->first()) {
                return new ErrorResource(new EntityNotFoundException('Product not found!'));
            }

            return new ProductResource($product);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @param ProductUpdateRequest $request
     * @return JsonResource
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResource
    {
        try {
            $product = $this->repository->update($id, $request->validated());

            return new ProductResource($product);
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
