<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Services\AkademikCalculationService;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    protected AkademikCalculationService $calculationService;

    public function __construct(AkademikCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'prodi.fakultas']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by fakultas
        if ($fakultasId = $request->get('fakultas_id')) {
            $query->whereHas('prodi', function ($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });
        }

        // Filter by prodi
        if ($prodiId = $request->get('prodi_id')) {
            $query->where('prodi_id', $prodiId);
        }

        // Filter by angkatan
        if ($angkatan = $request->get('angkatan')) {
            $query->where('angkatan', $angkatan);
        }

        // Variable Sorting
        $sortColumn = $request->get('sort', 'nim');
        $sortDirection = $request->get('order', 'asc');

        if ($sortColumn === 'name') {
            // Sort by relation is tricky in simple eloquent, usually requires join. 
            // For simplicity/performance without join, we skip or handle simpler.
            // Let's use leftJoin for robust sorting if requested, 
            // OR just stick to basic column sorting + NIM default for now to avoid complexity risk.
            // But User wants "Sort All Tables".
            // Implementation detail: Simple sortBy on Collection is slow for Pagination.
            // We use Join for Name sorting.
            $query->join('users', 'mahasiswa.user_id', '=', 'users.id')
                  ->select('mahasiswa.*') // Avoid column collision
                  ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'prodi') {
             $query->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                   ->select('mahasiswa.*')
                   ->orderBy('prodi.nama', $sortDirection);
        } else {
             // Default direct columns
             $query->orderBy($sortColumn, $sortDirection);
        }

        $mahasiswa = $query->paginate(config('siakad.pagination', 15))->withQueryString();
        
        $fakultasList = \App\Models\Fakultas::orderBy('nama')->get();
        // Eager load fakultas so we can access fakultas_id in the view for JS filtering
        $prodiList = Prodi::with('fakultas')->orderBy('nama')->get(); 
        $angkatanList = Mahasiswa::distinct()->pluck('angkatan')->sort()->reverse();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'fakultasList', 'prodiList', 'angkatanList'));
    }

    public function export(Request $request)
    {
        $query = Mahasiswa::with(['user', 'prodi']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }
        if ($fakultasId = $request->get('fakultas_id')) {
            $query->whereHas('prodi', function ($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });
        }
        if ($prodiId = $request->get('prodi_id')) {
            $query->where('prodi_id', $prodiId);
        }
        if ($angkatan = $request->get('angkatan')) {
            $query->where('angkatan', $angkatan);
        }

        // Export usually sorted by NIM
        $query->orderBy('nim');

        return response()->streamDownload(function() use ($query) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['NIM', 'Nama Mahasiswa', 'Prodi', 'Angkatan', 'Status', 'IPK']);

            $query->chunk(500, function($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->nim,
                        $row->user->name ?? '-',
                        $row->prodi->nama ?? '-',
                        $row->angkatan,
                        $row->status,
                        $row->ipk ?? 0,
                    ]);
                }
            });
            fclose($handle);
        }, 'data-mahasiswa-' . date('Y-m-d') . '.csv');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['user', 'prodi.fakultas', 'krs.tahunAkademik', 'krs.krsDetail.kelas.mataKuliah']);
        
        $ipkData = $this->calculationService->calculateIPK($mahasiswa);
        $ipsHistory = $this->calculationService->getIPSHistory($mahasiswa);
        $gradeDistribution = $this->calculationService->getGradeDistribution($mahasiswa);

        return view('admin.mahasiswa.show', compact('mahasiswa', 'ipkData', 'ipsHistory', 'gradeDistribution'));
    }
}
