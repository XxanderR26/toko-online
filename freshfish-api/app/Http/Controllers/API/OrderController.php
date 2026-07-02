<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    // ==========================
    // RIWAYAT PESANAN
    // ==========================
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->get();

        return response()->json([
            'message' => 'Riwayat pesanan berhasil diambil',
            'data' => $orders
        ]);
    }

    // ==========================
    // DETAIL PESANAN
    // ==========================
    public function show($id)
    {
        $order = Order::with('orderItems.product')->find($id);

        if (!$order) {

            return response()->json([
                'message' => 'Pesanan tidak ditemukan'
            ], 404);

        }

        $items = [];

        foreach ($order->orderItems as $item) {

            $items[] = [

                'produk'        => $item->product->name,
                'jumlah_kg'     => $item->jumlah_kg,
                'harga_per_kg'  => $item->harga_per_kg,
                'subtotal'      => $item->subtotal

            ];

        }

        return response()->json([

            'message' => 'Detail pesanan berhasil diambil',

            'data' => [

                'order_id'              => $order->id,
                'nama_penerima'         => $order->nama_penerima,
                'no_hp'                 => $order->no_hp,
                'alamat'                => $order->alamat,
                'kota'                  => $order->kota,

                'metode_pengiriman'     => $order->metode_pengiriman,
                'metode_pembayaran'     => $order->metode_pembayaran,

                'status'                => $order->status,
                'status_pembayaran'     => $order->status_pembayaran,

                'total_harga'           => $order->total_harga,
                'ongkir'                => $order->ongkir,
                'grand_total'           => $order->grand_total,

                'items' => $items

            ]

        ]);
    }
}
