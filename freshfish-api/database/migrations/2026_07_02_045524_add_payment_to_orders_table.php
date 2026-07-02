<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('metode_pembayaran')->after('grand_total');

            $table->string('status_pembayaran')
                  ->default('Belum Dibayar')
                  ->after('metode_pembayaran');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'metode_pembayaran',
                'status_pembayaran'
            ]);

        });
    }
};
