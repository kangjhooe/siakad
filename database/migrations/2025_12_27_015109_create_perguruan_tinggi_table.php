<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perguruan_tinggi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->nullable(); // Kode perguruan tinggi
            $table->enum('jenis', ['Universitas', 'Institut', 'Sekolah Tinggi', 'Politeknik', 'Akademi'])->default('Universitas');
            $table->enum('status', ['Negeri', 'Swasta'])->default('Negeri');
            $table->string('akreditasi')->nullable(); // A, B, C, dll
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('telepon')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('atas_nama_rekening')->nullable();
            $table->string('npwp')->nullable();
            $table->text('logo_path')->nullable(); // Path logo jika ada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perguruan_tinggi');
    }
};
