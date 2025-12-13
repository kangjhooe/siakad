<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        // Query for Main Table (Paginated & Sorted)
        $query = Ruangan::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_ruangan', 'like', "%{$search}%")
                  ->orWhere('kode_ruangan', 'like', "%{$search}%")
                  ->orWhere('gedung', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortColumn = $request->get('sort', 'kode_ruangan');
        $sortDirection = $request->get('order', 'asc');
        $allowedSorts = ['kode_ruangan', 'nama_ruangan', 'kapasitas', 'gedung', 'lantai', 'is_active'];

        if (in_array($sortColumn, $allowedSorts)) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('kode_ruangan', 'asc');
        }
        
        $ruanganList = $query->paginate(20)->withQueryString();
        
        // Stats Calculation (Separate from pagination)
        // We can optimize this by doing direct aggregates instead of fetching all objects
        $stats = [
            'total' => Ruangan::count(),
            'active' => Ruangan::where('is_active', true)->count(),
            'capacity' => Ruangan::sum('kapasitas'),
            'gedung_count' => Ruangan::distinct('gedung')->count('gedung'),
        ];
        
        return view('admin.ruangan.index', compact('ruanganList', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan',
            'nama_ruangan' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'nullable|string|max:50',
            'lantai' => 'nullable|integer|min:1',
            'fasilitas' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Ruangan::create($validated);

        return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan,' . $ruangan->id,
            'nama_ruangan' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'nullable|string|max:50',
            'lantai' => 'nullable|integer|min:1',
            'fasilitas' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $ruangan->update($validated);

        return redirect()->back()->with('success', 'Ruangan berhasil diupdate');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return redirect()->back()->with('success', 'Ruangan berhasil dihapus');
    }
}
