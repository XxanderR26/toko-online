<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'jumlah_kg' => 'required|numeric|min:0.1'
        ]);

        $cartItem = CartItem::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'jumlah_kg' => $request->jumlah_kg
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => $cartItem
        ]);
    }

    public function index()
    {
        $cartItems = CartItem::with('product')->get();

        $data = [];
        $totalHarga = 0;

        foreach ($cartItems as $item) {

            $subtotal = $item->jumlah_kg * $item->product->price_per_kg;

            $data[] = [
                'produk' => $item->product->name,
                'harga_per_kg' => $item->product->price_per_kg,
                'jumlah_kg' => $item->jumlah_kg,
                'subtotal' => $subtotal
            ];

            $totalHarga += $subtotal;
        }

        return response()->json([
            'message' => 'Data keranjang berhasil diambil',
            'data' => $data,
            'total_harga' => $totalHarga
        ]);
    }
}
