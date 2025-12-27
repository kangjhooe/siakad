<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\TahunAkademik;
use App\Services\AkademikCalculationService;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    protected $calculationService;

    public function __construct(AkademikCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Export Transkrip as printable HTML (PDF-ready)
     */
    public function transkrip()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $mahasiswa->load(['prodi.fakultas', 'dosenPa.user']);

        // Get all nilai
        $nilaiList = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->with('kelas.mataKuliah')
            ->get()
            ->sortBy('kelas.mataKuliah.kode_mk');

        // Calculate IPK
        $ipkData = $this->calculationService->calculateIPK($mahasiswa);

        // Get grade distribution
        $gradeDistribution = $this->calculationService->getGradeDistribution($mahasiswa);

        return view('mahasiswa.export.transkrip', compact('mahasiswa', 'nilaiList', 'ipkData', 'gradeDistribution'));
    }

    /**
     * Export KHS as printable HTML (PDF-ready)
     */
    public function khs(TahunAkademik $tahunAkademik)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $mahasiswa->load(['prodi.fakultas', 'dosenPa.user']);

        // Check if student has approved KRS for this semester
        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->where('status', 'approved')
            ->first();

        if (!$krs) {
            return redirect()->back()->with('error', 'KRS untuk semester ini belum diapprove');
        }

        // Get all grades for this semester
        $nilaiList = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelas', function ($q) use ($tahunAkademik) {
                $q->whereHas('krsDetail.krs', fn($q2) => $q2->where('tahun_akademik_id', $tahunAkademik->id));
            })
            ->with(['kelas.mataKuliah', 'kelas.dosen.user'])
            ->get()
            ->sortBy('kelas.mataKuliah.kode_mk');

        // Calculate IPS for this semester
        $ipsData = $this->calculationService->calculateIPS($mahasiswa, $tahunAkademik->id);

        // Get IPK cumulative
        $ipkData = $this->calculationService->calculateIPK($mahasiswa);

        return view('mahasiswa.export.khs', compact('mahasiswa', 'tahunAkademik', 'nilaiList', 'ipsData', 'ipkData'));
    }

    /**
     * Export KRS as printable HTML (PDF-ready)
     */
    public function krs()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $mahasiswa->load(['prodi.fakultas', 'dosenPa.user']);

        // Get active tahun akademik
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        
        if (!$tahunAktif) {
            return redirect()->route('mahasiswa.krs.index')->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get KRS for active tahun akademik (can be pending or approved, but not draft)
        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->whereIn('status', ['pending', 'approved'])
            ->with(['tahunAkademik', 'krsDetail.kelas.mataKuliah', 'krsDetail.kelas.dosen.user'])
            ->first();

        if (!$krs) {
            return redirect()->route('mahasiswa.krs.index')->with('error', 'KRS untuk semester ini belum diajukan atau belum disetujui. Hanya KRS yang sudah diajukan atau disetujui yang dapat dicetak.');
        }

        // Calculate total SKS
        $totalSks = $krs->krsDetail->sum(fn($detail) => $detail->kelas->mataKuliah->sks ?? 0);

        return view('mahasiswa.export.krs', compact('mahasiswa', 'krs', 'totalSks'));
    }
}
