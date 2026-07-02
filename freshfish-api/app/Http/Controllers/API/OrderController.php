<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->get();

        return response()->json([
            'message' => 'Riwayat pesanan berhasil diambil',
            'data' => $orders
        ]);
    }
}
