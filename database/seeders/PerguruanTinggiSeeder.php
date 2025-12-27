<?php

namespace Database\Seeders;

use App\Models\PerguruanTinggi;
use Illuminate\Database\Seeder;

class PerguruanTinggiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya buat jika belum ada
        if (PerguruanTinggi::count() === 0) {
            PerguruanTinggi::create([
                'nama' => 'Universitas Anda',
                'kode' => null,
                'jenis' => 'Universitas',
                'status' => 'Negeri',
                'akreditasi' => null,
                'alamat' => null,
                'kota' => null,
                'provinsi' => null,
                'kode_pos' => null,
                'telepon' => null,
                'fax' => null,
                'email' => null,
                'website' => null,
                'nomor_rekening' => null,
                'nama_bank' => null,
                'atas_nama_rekening' => null,
                'npwp' => null,
                'logo_path' => null,
            ]);
        }
    }
}
