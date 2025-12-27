<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerguruanTinggi extends Model
{
    use HasFactory;

    protected $table = 'perguruan_tinggi';

    protected $fillable = [
        'nama',
        'kode',
        'jenis',
        'status',
        'akreditasi',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'fax',
        'email',
        'website',
        'nomor_rekening',
        'nama_bank',
        'atas_nama_rekening',
        'npwp',
        'logo_path',
    ];

    /**
     * Get the single perguruan tinggi instance (singleton)
     * Create default if not exists
     */
    public static function getInstance(): self
    {
        $pt = static::first();
        
        if (!$pt) {
            $pt = static::create([
                'nama' => 'Universitas Anda',
                'jenis' => 'Universitas',
                'status' => 'Negeri',
            ]);
        }
        
        return $pt;
    }

    /**
     * Get full address
     */
    public function getAlamatLengkapAttribute(): string
    {
        $parts = array_filter([
            $this->alamat,
            $this->kota,
            $this->provinsi,
            $this->kode_pos ? "Kode Pos: {$this->kode_pos}" : null,
        ]);
        
        return implode(', ', $parts);
    }
}
