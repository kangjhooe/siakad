<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AkademikService;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    protected $akademikService;

    public function __construct(AkademikService $akademikService)
    {
        $this->akademikService = $akademikService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\MataKuliah::query();

        // 1. Filter Category (Prefix)
        // Note: Using hardcoded logic matching the view's previous behavior
        if ($request->filled('category')) {
            $query->where('kode_mk', 'like', $request->category . '%');
        }

        // 2. Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        // 3. Sorting
        $sortColumn = $request->get('sort', 'kode_mk');
        $sortDirection = $request->get('order', 'asc');
        
        // Whitelist columns to prevent SQL injection or errors
        $allowedSorts = ['kode_mk', 'nama_mk', 'sks', 'semester', 'created_at'];
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('kode_mk', 'asc');
        }

        // 4. Pagination
        $mataKuliah = $query->paginate(50)->withQueryString();

        return view('admin.mata-kuliah.index', compact('mataKuliah'));
    }

    public function export(Request $request)
    {
        $query = \App\Models\MataKuliah::query();

        if ($request->filled('category')) {
            $query->where('kode_mk', 'like', $request->category . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        $sortColumn = $request->get('sort', 'kode_mk');
        $sortDirection = $request->get('order', 'asc');
        $allowedSorts = ['kode_mk', 'nama_mk', 'sks', 'semester', 'created_at'];
        
        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        }

        return response()->streamDownload(function() use ($query) {
            $handle = fopen('php://output', 'w');
            
            // BOM for Excel
            fputs($handle, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($handle, ['Kode MK', 'Nama Mata Kuliah', 'SKS', 'Semester', 'Dibuat Pada']);

            $query->chunk(500, function($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->kode_mk,
                        $row->nama_mk,
                        $row->sks,
                        $row->semester,
                        $row->created_at,
                    ]);
                }
            });

            fclose($handle);
        }, 'data-mata-kuliah-' . date('Y-m-d-H-i') . '.csv');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk'  => 'required|string|unique:mata_kuliah,kode_mk',
            'nama_mk'  => 'required|string',
            'sks'      => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
        ]);
        $this->akademikService->createMataKuliah($validated);
        return redirect()->back()->with('success', 'Mata Kuliah berhasil ditambahkan');
    }

    public function update(Request $request, \App\Models\MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode_mk'  => 'required|string|unique:mata_kuliah,kode_mk,' . $mataKuliah->id,
            'nama_mk'  => 'required|string',
            'sks'      => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
        ]);
        $mataKuliah->update($validated);
        return redirect()->back()->with('success', 'Mata Kuliah berhasil diupdate');
    }

    public function destroy(\App\Models\MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->back()->with('success', 'Mata Kuliah berhasil dihapus');
    }
}
