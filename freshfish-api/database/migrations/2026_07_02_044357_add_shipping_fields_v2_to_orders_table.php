<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('metode_pengiriman')->after('kota');

            $table->decimal('ongkir',12,2)->default(0)->after('metode_pengiriman');

            $table->decimal('grand_total',12,2)->default(0)->after('ongkir');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'metode_pengiriman',
                'ongkir',
                'grand_total'
            ]);

        });
    }
};
