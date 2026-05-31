<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = category::all();

        return response()->json([
            'message' => 'Data kategori berhasil diambil',
            'data' => $categories
        ]);
    }
}
