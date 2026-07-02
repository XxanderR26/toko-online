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
            'user_id'         => 'required',
            'nama_penerima'  => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'alamat'         => 'required|string',
            'kota'           => 'required|string|max:100'
        ]);

        $cartItems = CartItem::with('product')
                    ->where('user_id', $request->user_id)
                    ->get();

        // CEK APAKAH KERANJANG KOSONG
        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Keranjang masih kosong'
            ], 400);
        }

        // CEK STOK SEMUA PRODUK
        foreach ($cartItems as $item) {

            if ($item->jumlah_kg > $item->product->stock) {

                return response()->json([
                    'message' => 'Stok ' . $item->product->name . ' tidak mencukupi'
                ], 400);
            }
        }

        // HITUNG TOTAL HARGA
        $totalHarga = 0;

        foreach ($cartItems as $item) {

            $subtotal = $item->jumlah_kg * $item->product->price_per_kg;

            $totalHarga += $subtotal;
        }

        // SIMPAN DATA ORDER
        $order = Order::create([
            'user_id'         => $request->user_id,
            'total_harga'     => $totalHarga,
            'status'          => 'pending',
            'nama_penerima'   => $request->nama_penerima,
            'no_hp'           => $request->no_hp,
            'alamat'          => $request->alamat,
            'kota'            => $request->kota
        ]);

        // SIMPAN DETAIL PESANAN DAN KURANGI STOK
        foreach ($cartItems as $item) {

            $subtotal = $item->jumlah_kg * $item->product->price_per_kg;

            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item->product_id,
                'jumlah_kg'     => $item->jumlah_kg,
                'harga_per_kg'  => $item->product->price_per_kg,
                'subtotal'      => $subtotal
            ]);

            // Kurangi stok produk
            $item->product->stock -= $item->jumlah_kg;
            $item->product->save();
        }

        // HAPUS ISI KERANJANG
        CartItem::where('user_id', $request->user_id)->delete();

        return response()->json([
            'message' => 'Checkout berhasil',
            'data' => [
                'order_id'        => $order->id,
                'nama_penerima'   => $order->nama_penerima,
                'no_hp'           => $order->no_hp,
                'alamat'          => $order->alamat,
                'kota'            => $order->kota,
                'status'          => $order->status,
                'total_harga'     => $totalHarga
            ]
        ]);
    }
}
