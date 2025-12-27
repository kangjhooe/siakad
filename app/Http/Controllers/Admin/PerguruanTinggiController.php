<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerguruanTinggi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerguruanTinggiController extends Controller
{
    /**
     * Check if user is superadmin
     */
    private function authorizeSuperAdmin(): void
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya superadmin yang dapat mengelola profil perguruan tinggi.');
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();
        
        $perguruanTinggi = PerguruanTinggi::getInstance();
        
        return view('admin.perguruan-tinggi.index', compact('perguruanTinggi'));
    }

    public function update(Request $request)
    {
        $this->authorizeSuperAdmin();
        
        $perguruanTinggi = PerguruanTinggi::getInstance();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
            'jenis' => 'required|in:Universitas,Institut,Sekolah Tinggi,Politeknik,Akademi',
            'status' => 'required|in:Negeri,Swasta',
            'akreditasi' => 'nullable|string|max:10',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'nomor_rekening' => 'nullable|string|max:50',
            'nama_bank' => 'nullable|string|max:100',
            'atas_nama_rekening' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($perguruanTinggi->logo_path && Storage::disk('public')->exists($perguruanTinggi->logo_path)) {
                Storage::disk('public')->delete($perguruanTinggi->logo_path);
            }

            // Store new logo
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('logo', $filename, 'public');
            
            $validated['logo_path'] = $path;
        }

        $perguruanTinggi->update($validated);

        return redirect()->back()->with('success', 'Profil perguruan tinggi berhasil diperbarui');
    }
}
