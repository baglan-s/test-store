<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Exceptions\EntityNotFoundException;
use App\Repositories\CartRepository;
use App\Http\Resources\CartResource;
use App\Http\Requests\CartRequest;
use App\Http\Requests\CartClearRequest;

class CartController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\CartRepository $repository
     */
    public function __construct(
        private CartRepository $repository
    ) {}
    
    /**
     * Summary of show
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->repository->getCartData($id),
        ]);
    }
    
    /**
     * Summary of add item
     * @param \App\Http\Requests\CartRequest $request
     * @param int $id
     * @return JsonResponse|JsonResource
     */
    public function add(CartRequest $request, int $id): JsonResponse|JsonResource
    {
        try {
            $this->repository->add($id, $request->product_id, $request->quantity);

            return new JsonResponse([
                'data' => $this->repository->getCartData($id),
            ]);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Summary of remove item
     * @param \App\Http\Requests\CartRequest $request
     * @param int $id
     * @return JsonResponse|JsonResource
     */
    public function remove(CartRequest $request, int $id): JsonResponse|JsonResource
    {
        try {
            $this->repository->remove($id, $request->product_id, $request->quantity);

            return new JsonResponse([
                'data' => $this->repository->getCartData($id),
            ]);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * Summary of cart clear
     * @param int $id
     * @return JsonResponse|JsonResource
     */
    public function clear(int $id): JsonResponse|JsonResource
    {
        try {
            $this->repository->clear($id);

            return new JsonResponse([
                'data' => $this->repository->getCartData($id),
            ]);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }
}
