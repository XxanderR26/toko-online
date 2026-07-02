<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $cartItems = CartItem::with('product')
                    ->where('user_id', $request->user_id)
                    ->get();

        // CEK KERANJANG KOSONG
        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang masih kosong'
            ], 400);
        }

        // CEK STOK
        foreach ($cartItems as $item) {

            if ($item->jumlah_kg > $item->product->stock) {

                return response()->json([
                    'message' => 'Stok '.$item->product->name.' tidak mencukupi'
                ], 400);
            }
        }

        // HITUNG TOTAL HARGA
        $totalHarga = 0;

        foreach ($cartItems as $item) {

            $subtotal = $item->jumlah_kg * $item->product->price_per_kg;

            $totalHarga += $subtotal;
        }

        // SIMPAN ORDER
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_harga' => $totalHarga,
            'status' => 'pending'
        ]);

        // SIMPAN ORDER ITEM DAN KURANGI STOK
        foreach ($cartItems as $item) {

            $subtotal = $item->jumlah_kg * $item->product->price_per_kg;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'jumlah_kg' => $item->jumlah_kg,
                'harga_per_kg' => $item->product->price_per_kg,
                'subtotal' => $subtotal
            ]);

            // Kurangi stok
            $item->product->stock -= $item->jumlah_kg;
            $item->product->save();
        }

        // HAPUS KERANJANG
        CartItem::where('user_id', $request->user_id)->delete();

        return response()->json([
            'message' => 'Checkout berhasil',
            'order_id' => $order->id,
            'total_harga' => $totalHarga
        ]);
    }
}
