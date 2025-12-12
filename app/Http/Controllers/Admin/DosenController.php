<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::with(['user', 'prodi.fakultas'])
            ->withCount('kelas');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nidn', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by prodi
        if ($prodiId = $request->get('prodi_id')) {
            $query->where('prodi_id', $prodiId);
        }

        $dosen = $query->orderBy('nidn')->paginate(config('siakad.pagination', 15));
        $prodiList = Prodi::with('fakultas')->get();

        return view('admin.dosen.index', compact('dosen', 'prodiList'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load(['user', 'prodi.fakultas', 'kelas.mataKuliah', 'kelas.krsDetail']);
        
        // Paginate kelas (4 per page)
        $kelasIds = $dosen->kelas()->pluck('id');
        $teachingLoad = $dosen->kelas()->with(['mataKuliah', 'krsDetail'])->paginate(4);

        // Calculate totals for stats (based on all classes, not just paginated ones)
        $totalSks = $dosen->kelas->sum(fn($k) => $k->mataKuliah->sks);
        $totalStudents = \App\Models\KrsDetail::whereIn('kelas_id', $kelasIds)->count();

        // Pass Nilai counts for the view (we can do this in view or prepare here)
        // Since we are paginating, we can accept doing count queries in the view for the 4 items, 
        // or eager load. Let's optimize by eager loading relationships if possible, 
        // but Nilai count is complex. We'll use a helper or simple query in view for now as it is strictly 4 items.

        return view('admin.dosen.show', compact('dosen', 'teachingLoad', 'totalSks', 'totalStudents'));
    }
}
