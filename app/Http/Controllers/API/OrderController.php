<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\OrderCreateRequest;

class OrderController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\OrderRepository $repository
     */
    public function __construct(
        private OrderRepository $repository
    ) {}

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return OrderResource::collection(
            Auth::user()
                ->orders()
                ->paginate($request->limit ?? 20)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(OrderCreateRequest $request): JsonResource
    {
        try {
            return new OrderResource($this->repository->create($request->validated()));
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
            if (!$order = Auth::user()->orders()->where('id', $id)->first()) {
                throw new EntityNotFoundException('Order not found!');
            }

            return new OrderResource($order);
        } catch (\Exception $e) {
            return new ErrorResource($e);
        }
    }
}
