<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';

    protected $fillable = [
        'fakultas_id',
        'nama',
        'kepala_prodi_id',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->hasMany(Dosen::class);
    }

    /**
     * Kepala Program Studi (dosen yang diangkat sebagai kaprodi)
     */
    public function kepalaProdi()
    {
        return $this->belongsTo(Dosen::class, 'kepala_prodi_id');
    }
}
