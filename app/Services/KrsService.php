<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;
use Exception;

class KrsService
{
    public function getActiveKrsOrNew(Mahasiswa $mahasiswa)
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        if (!$tahunAktif) {
            throw new Exception('Tidak ada tahun akademik yang aktif.');
        }

        return Krs::firstOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'tahun_akademik_id' => $tahunAktif->id
            ],
            ['status' => 'draft']
        );
    }

    public function addKelas(Krs $krs, $kelasId)
    {
        return DB::transaction(function () use ($krs, $kelasId) {
            if ($krs->status !== 'draft') {
                throw new Exception('KRS sudah disubmit/final. Tidak bisa ubah.');
            }

            $kelas = Kelas::with('mataKuliah')->findOrFail($kelasId);
            
            // 1. Cek Kapasitas
            $terisi = KrsDetail::where('kelas_id', $kelasId)->count();
            if ($terisi >= $kelas->kapasitas) {
                throw new Exception("Kelas penuh! Kapasitas: {$kelas->kapasitas}");
            }

            // 2. Cek apakah mata kuliah sudah diambil di KRS ini (beda kelas)
            $mkTaken = $krs->krsDetail()->whereHas('kelas', function($q) use ($kelas) {
                $q->where('mata_kuliah_id', $kelas->mata_kuliah_id);
            })->exists();

            if ($mkTaken) {
                throw new Exception("Mata kuliah {$kelas->mataKuliah->nama_mk} sudah diambil.");
            }

            // 3. Cek Batas SKS
            $sksSaatIni = $krs->krsDetail->sum(fn($detail) => $detail->kelas->mataKuliah->sks);
            $sksBaru = $kelas->mataKuliah->sks;
            
            // Hitung jatah SKS (Logic IPS Semester Lalu)
            // Untuk sederhananya kita ambil default atau logic real
            // Disini kita ambil max sks dari config 'default' dulu jika IPS tidak ada
            // TODO: Implement calculation based on IPS logic
            $maxSks = config('siakad.maks_sks.default', 24); 

            if (($sksSaatIni + $sksBaru) > $maxSks) {
                throw new Exception("Melebihi batas SKS ({$maxSks}). Total SKS akan menjadi: " . ($sksSaatIni + $sksBaru));
            }

            // Add
            return KrsDetail::create([
                'krs_id' => $krs->id,
                'kelas_id' => $kelasId
            ]);
        });
    }

    public function removeKelas(Krs $krs, $detailId)
    {
        if ($krs->status !== 'draft') {
            throw new Exception('KRS terkunci.');
        }

        $detail = $krs->krsDetail()->findOrFail($detailId);
        $detail->delete();
    }

    public function submitKrs(Krs $krs)
    {
        if ($krs->krsDetail()->count() === 0) {
            throw new Exception("KRS kosong tidak dapat diajukan.");
        }
        $krs->update(['status' => 'pending']);
    }

    public function approveKrs(Krs $krs)
    {
        $krs->update(['status' => 'approved']);
    }

    public function rejectKrs(Krs $krs)
    {
        $krs->update(['status' => 'rejected']);
    }
}
