<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('nama_penerima')->after('status');

            $table->string('no_hp')->after('nama_penerima');

            $table->text('alamat')->after('no_hp');

            $table->string('kota')->after('alamat');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'nama_penerima',
                'no_hp',
                'alamat',
                'kota'
            ]);

        });
    }
};
