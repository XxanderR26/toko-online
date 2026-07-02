<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',

        'total_harga',

        'status',

        'nama_penerima',

        'no_hp',

        'alamat',

        'kota',

        'metode_pengiriman',

        'ongkir',

        'grand_total'

    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
