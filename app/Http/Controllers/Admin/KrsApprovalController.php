<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use Illuminate\Http\Request;

class KrsApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $krsList = Krs::with(['mahasiswa.user', 'mahasiswa.prodi', 'tahunAkademik', 'krsDetail.kelas.mataKuliah'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($status !== 'all', fn($q) => $q->where('status', $status));

        // Sorting
        $sortColumn = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('order', 'desc');

        if ($sortColumn === 'name') {
            $krsList = $krsList->join('mahasiswa', 'krs.mahasiswa_id', '=', 'mahasiswa.id')
                             ->join('users', 'mahasiswa.user_id', '=', 'users.id')
                             ->select('krs.*')
                             ->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'nim') {
            $krsList = $krsList->join('mahasiswa', 'krs.mahasiswa_id', '=', 'mahasiswa.id')
                             ->select('krs.*')
                             ->orderBy('mahasiswa.nim', $sortDirection);
        } elseif ($sortColumn === 'prodi') {
             $krsList = $krsList->join('mahasiswa', 'krs.mahasiswa_id', '=', 'mahasiswa.id')
                              ->join('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')
                              ->select('krs.*')
                              ->orderBy('prodi.nama', $sortDirection);
        } elseif ($sortColumn === 'status') {
             $krsList = $krsList->orderBy('status', $sortDirection);
        } else {
             $krsList = $krsList->orderBy('updated_at', 'desc');
        }

        $krsList = $krsList->paginate(config('siakad.pagination', 15))->withQueryString();

        $statusCounts = [
            'pending' => Krs::where('status', 'pending')->count(),
            'approved' => Krs::where('status', 'approved')->count(),
            'rejected' => Krs::where('status', 'rejected')->count(),
            'draft' => Krs::where('status', 'draft')->count(),
        ];

        return view('admin.krs-approval.index', compact('krsList', 'status', 'statusCounts'));
    }

    public function show(Krs $krs)
    {
        $krs->load(['mahasiswa.user', 'mahasiswa.prodi', 'tahunAkademik', 'krsDetail.kelas.mataKuliah', 'krsDetail.kelas.dosen.user']);
        
        $totalSks = $krs->krsDetail->sum(fn($d) => $d->kelas->mataKuliah->sks);

        return view('admin.krs-approval.show', compact('krs', 'totalSks'));
    }

    public function approve(Request $request, Krs $krs)
    {
        if ($krs->status !== 'pending') {
            return redirect()->back()->with('error', 'KRS tidak dalam status pending');
        }

        $krs->update(['status' => 'approved']);

        return redirect()->route('admin.krs-approval.index')
            ->with('success', 'KRS mahasiswa ' . $krs->mahasiswa->user->name . ' berhasil disetujui');
    }

    public function reject(Request $request, Krs $krs)
    {
        if ($krs->status !== 'pending') {
            return redirect()->back()->with('error', 'KRS tidak dalam status pending');
        }

        $krs->update(['status' => 'rejected']);

        return redirect()->route('admin.krs-approval.index')
            ->with('success', 'KRS mahasiswa ' . $krs->mahasiswa->user->name . ' ditolak');
    }

    public function bulkApprove(Request $request)
    {
        $ids = $request->input('krs_ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu KRS');
        }

        Krs::whereIn('id', $ids)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        return redirect()->back()->with('success', count($ids) . ' KRS berhasil disetujui');
    }
}
