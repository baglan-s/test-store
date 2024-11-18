<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ProductRepository;
use Illuminate\Support\Collection as Collection;

class CartRepository
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function add(int $cartId, int $productId, int $quantity): void
    {
        $cart = $this->getOrCreateCart($cartId);
        $cartItem = $cart->items()->firstOrCreate(['product_id' => $productId]);
        $cartItem->increment('quantity', $quantity);
    }

    public function remove(int $cartId, int $productId, int $quantity): void
    {
        $cart = $this->getOrCreateCart($cartId);
        
        if (!$cartItem = $cart->items()->where('product_id', $productId)->first()) {
            throw new \Exception('Item not found in the cart', 404);
        }

        if ($quantity <= 0 || $cartItem->quantity < $quantity) {
            throw new \Exception('Invalid quantity', 422);
        }

        $cartItem->quantity === $quantity ? $cartItem->delete() : $cartItem->decrement('quantity', $quantity);
    }

    public function clear(int $cartId): void
    {
        $cart = $this->getOrCreateCart($cartId);
        $cart->items()->delete();
    }

    public function getOrCreateCart(int $cartId): Cart
    {
        if ($user = Auth::user()) {
            return $user->cart ?? $user->cart()->create([]);
        }

        return Cart::find($cartId) ?? Cart::create([]);
    }

    public function getCartProducts(int $cartId): Collection
    {
        $cartProducts = [];
        $cartItems = $this->getOrCreateCart($cartId)->items;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $product->quanity = $item->quantity;
            $product->total_price = $item->quantity * $product->price;
            $cartProducts[] = $product;
        }

        return collect($cartProducts);
    }

    public function getCartData(int $cartId): array
    {
        $cartProducts =  $this->getCartProducts($cartId);

        return [
            'items' => $cartProducts,
            'total_price' => $cartProducts->sum('total_price'),
            'total_quantity' => $cartProducts->sum('quantity'),
        ];
    }
}