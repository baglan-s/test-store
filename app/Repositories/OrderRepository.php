<?php

namespace App\Repositories;

use App\Models\Order;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CartRepository;

class OrderRepository
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function model(): Order
    {
        return new Order();
    }

    public function getById(int $id): Order | null
    {
        return Order::find($id);
    }

    public function create(array $attributes): Order
    {
        try {
            DB::beginTransaction();

            if (isset($attributes['cart_id'])) {
                $cart = $this->cartRepository->getOrCreateCart($attributes['cart_id']);
                $order = Order::create($attributes);
            } else {
                $user = Auth::user();
                $cart = $user->cart;
                $order = $cart->orders()->create([
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone,
                ]);
            }

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'sum' => $item->quantity * $item->product->price,
                ]);
            }

            $this->cartRepository->clear($cart->id);

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update(int $id, array $attributes): Order
    {
        if (!$order = $this->getById($id)) {
            throw new EntityNotFoundException('Order not found!');
        }

        $order->update($attributes);

        return $this->getById($id);
    }

    public function delete(int $id)
    {
        if (!$order = $this->getById($id)) {
            throw new EntityNotFoundException('Order not found!');
        }

        $order->delete();
    }
}