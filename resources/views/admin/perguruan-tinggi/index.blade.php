<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-siakad-dark dark:text-white">Profil Perguruan Tinggi</h2>
                <p class="text-sm text-siakad-secondary dark:text-gray-400 mt-1">
                    Kelola informasi dan identitas perguruan tinggi
                </p>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <p class="text-sm text-green-800 dark:text-green-400">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Form Card -->
    <div class="card-saas dark:bg-gray-800 p-6">
        <form action="{{ route('admin.perguruan-tinggi.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo Upload Section -->
            <div class="border-b border-siakad-light dark:border-gray-700 pb-6 mb-6 px-2">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Logo Perguruan Tinggi
                </h3>
                
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                    <!-- Logo Preview -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 border-2 border-dashed border-siakad-light dark:border-gray-700 rounded-lg flex items-center justify-center bg-siakad-light/30 dark:bg-gray-700/30 overflow-hidden p-2">
                            @if($perguruanTinggi->logo_path && Storage::disk('public')->exists($perguruanTinggi->logo_path))
                                <img id="logoPreview" src="{{ Storage::url($perguruanTinggi->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <div id="logoPreview" class="text-center p-2">
                                    <svg class="w-8 h-8 text-siakad-secondary dark:text-gray-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-[10px] text-siakad-secondary dark:text-gray-400">Belum ada logo</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Input -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-3">Upload Logo</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="file" name="logo" id="logoInput" accept="image/*" class="hidden" onchange="previewLogo(this)">
                                <div class="input-saas w-full px-4 py-3 text-sm text-center cursor-pointer hover:bg-siakad-light/50 dark:hover:bg-gray-700 transition border-2 border-dashed border-siakad-primary/30 dark:border-blue-500/30 rounded-lg">
                                    <svg class="w-5 h-5 inline-block mr-2 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <span class="text-siakad-primary dark:text-blue-400 font-medium">Pilih File</span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-3 text-xs text-siakad-secondary dark:text-gray-400 leading-relaxed">
                            Format: JPG, PNG, GIF, SVG (Maks. 2MB). Logo akan muncul di kop surat KRS, KHS, dan Transkrip.
                        </p>
                        @error('logo')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Identitas Perguruan Tinggi -->
            <div class="border-b border-siakad-light dark:border-gray-700 pb-6 mb-6 px-2">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Identitas Perguruan Tinggi
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Nama Perguruan Tinggi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $perguruanTinggi->nama) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                        @error('nama')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode', $perguruanTinggi->kode) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Kode perguruan tinggi">
                        @error('kode')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Jenis <span class="text-red-500">*</span></label>
                        <select name="jenis" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                            <option value="Universitas" {{ old('jenis', $perguruanTinggi->jenis) == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                            <option value="Institut" {{ old('jenis', $perguruanTinggi->jenis) == 'Institut' ? 'selected' : '' }}>Institut</option>
                            <option value="Sekolah Tinggi" {{ old('jenis', $perguruanTinggi->jenis) == 'Sekolah Tinggi' ? 'selected' : '' }}>Sekolah Tinggi</option>
                            <option value="Politeknik" {{ old('jenis', $perguruanTinggi->jenis) == 'Politeknik' ? 'selected' : '' }}>Politeknik</option>
                            <option value="Akademi" {{ old('jenis', $perguruanTinggi->jenis) == 'Akademi' ? 'selected' : '' }}>Akademi</option>
                        </select>
                        @error('jenis')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                            <option value="Negeri" {{ old('status', $perguruanTinggi->status) == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                            <option value="Swasta" {{ old('status', $perguruanTinggi->status) == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                        </select>
                        @error('status')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Akreditasi</label>
                        <input type="text" name="akreditasi" value="{{ old('akreditasi', $perguruanTinggi->akreditasi) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="A, B, C, dll">
                        @error('akreditasi')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Alamat & Kontak -->
            <div class="border-b border-siakad-light dark:border-gray-700 pb-6 mb-6 px-2">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Alamat & Kontak
                </h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Alamat</label>
                        <textarea name="alamat" rows="3" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Alamat lengkap perguruan tinggi">{{ old('alamat', $perguruanTinggi->alamat) }}</textarea>
                        @error('alamat')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Kota</label>
                            <input type="text" name="kota" value="{{ old('kota', $perguruanTinggi->kota) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            @error('kota')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $perguruanTinggi->provinsi) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            @error('provinsi')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $perguruanTinggi->kode_pos) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                            @error('kode_pos')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $perguruanTinggi->telepon) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="(021) 1234567">
                            @error('telepon')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Fax</label>
                            <input type="text" name="fax" value="{{ old('fax', $perguruanTinggi->fax) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="(021) 1234568">
                            @error('fax')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $perguruanTinggi->email) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="info@universitas.ac.id">
                            @error('email')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Website</label>
                            <input type="url" name="website" value="{{ old('website', $perguruanTinggi->website) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="https://www.universitas.ac.id">
                            @error('website')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Keuangan -->
            <div class="border-b border-siakad-light dark:border-gray-700 pb-6 mb-6 px-2">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Keuangan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $perguruanTinggi->nomor_rekening) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="1234567890">
                        @error('nomor_rekening')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Nama Bank</label>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank', $perguruanTinggi->nama_bank) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Bank Mandiri, BCA, dll">
                        @error('nama_bank')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">Atas Nama Rekening</label>
                        <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening', $perguruanTinggi->atas_nama_rekening) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Nama pemilik rekening">
                        @error('atas_nama_rekening')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2.5">NPWP</label>
                        <input type="text" name="npwp" value="{{ old('npwp', $perguruanTinggi->npwp) }}" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="01.234.567.8-901.000">
                        @error('npwp')<p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-6 px-2">
                <button type="submit" class="btn-primary-saas px-6 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="w-full h-full object-contain p-2">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
