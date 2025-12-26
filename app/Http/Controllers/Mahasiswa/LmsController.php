<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class LmsController extends Controller
{
    /**
     * Show LMS dashboard with enrolled kelas
     */
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            abort(403, 'Anda tidak memiliki akses sebagai mahasiswa.');
        }

        // Get enrolled kelas
        $krs = $mahasiswa->krs()->where('status', 'approved')->first();
        $kelasIds = $krs?->krsDetail?->pluck('kelas_id')->toArray() ?? [];

        $kelasList = Kelas::whereIn('id', $kelasIds)
            ->with(['mataKuliah', 'dosen.user', 'tugas' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->map(function($kelas) use ($mahasiswa) {
                // Count pending tugas (not submitted yet)
                $submittedTugasIds = $mahasiswa->tugasSubmissions()->pluck('tugas_id')->toArray();
                $kelas->pending_tugas = $kelas->tugas->whereNotIn('id', $submittedTugasIds)->count();
                return $kelas;
            });

        return view('mahasiswa.lms.index', compact('kelasList'));
    }
}
