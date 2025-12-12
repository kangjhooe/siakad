<x-app-layout>
    <x-slot name="header">
        Data Mata Kuliah
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-siakad-secondary dark:text-gray-400">Kelola data mata kuliah berdasarkan kategori</p>
        </div>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn-primary-saas px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Mata Kuliah
        </button>
    </div>

    @php
        // Mapping prefix kode ke nama kategori
        $categoryNames = [
            'TI' => 'Teknik Informatika',
            'SI' => 'Sistem Informasi',
            'TE' => 'Teknik Elektro',
            'MN' => 'Manajemen',
            'AK' => 'Akuntansi',
            'MT' => 'Matematika',
            'MK' => 'Mata Kuliah Umum',
            'UN' => 'Mata Kuliah Universitas',
        ];
        
        // Group mata kuliah by prefix (first 2 characters)
        $grouped = $mataKuliah->groupBy(function($mk) {
            return strtoupper(substr($mk->kode_mk, 0, 2));
        })->sortKeys();
    @endphp

    <!-- Mata Kuliah Grouped by Category (Collapsible) -->
    @foreach($grouped as $prefix => $courses)
    <div class="card-saas overflow-hidden mb-4 dark:bg-gray-800" x-data="{ open: false }">
        <!-- Category Header (Clickable) -->
        <button @click="open = !open" type="button" class="w-full px-6 py-4 bg-siakad-primary/5 dark:bg-gray-700/30 border-b border-siakad-light dark:border-gray-700 flex items-center justify-between hover:bg-siakad-primary/10 dark:hover:bg-gray-700/50 transition cursor-pointer text-left">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-primary dark:bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-sm">{{ $prefix }}</span>
                </div>
                <div>
                    <h3 class="font-semibold text-siakad-dark dark:text-white">{{ $categoryNames[$prefix] ?? 'Kategori ' . $prefix }}</h3>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">{{ $courses->count() }} Mata Kuliah • {{ $courses->sum('sks') }} SKS Total</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2">
                    @foreach($courses->groupBy('semester')->sortKeys()->take(4) as $sem => $semCourses)
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium bg-siakad-secondary/10 text-siakad-secondary dark:bg-gray-700 dark:text-gray-300 rounded">Sem {{ $sem }}: {{ $semCourses->count() }}</span>
                    @endforeach
                    @if($courses->groupBy('semester')->count() > 4)
                    <span class="text-xs text-siakad-secondary dark:text-gray-400">+{{ $courses->groupBy('semester')->count() - 4 }}</span>
                    @endif
                </div>
                <!-- Chevron Icon -->
                <svg class="w-5 h-5 text-siakad-secondary dark:text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </button>

        <!-- Mata Kuliah Table (Collapsible Content) -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-siakad-light/30 dark:bg-gray-900">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-28">Kode</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-20">SKS</th>
                        <th class="text-center py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-28">Semester</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses->sortBy('semester') as $idx => $mk)
                    <tr class="border-b border-siakad-light/50 dark:border-gray-700/50 hover:bg-siakad-light/10 dark:hover:bg-gray-700/30 transition">
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $idx + 1 }}</td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-mono text-siakad-primary dark:text-blue-400 font-medium">{{ $mk->kode_mk }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ $mk->nama_mk }}</span>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-primary/10 text-siakad-primary dark:bg-blue-500/10 dark:text-blue-400 rounded-full">{{ $mk->sks }}</span>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-secondary/10 text-siakad-secondary dark:bg-gray-700 dark:text-gray-300 rounded-full">Sem {{ $mk->semester }}</span>
                        </td>
                        <td class="py-4 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="editMK({{ $mk->id }}, '{{ $mk->kode_mk }}', '{{ addslashes($mk->nama_mk) }}', {{ $mk->sks }}, {{ $mk->semester }})" class="p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('admin.mata-kuliah.destroy', $mk) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-siakad-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    @if($mataKuliah->isEmpty())
    <div class="card-saas p-12 text-center dark:bg-gray-800">
        <div class="w-16 h-16 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <p class="text-siakad-dark dark:text-white font-medium mb-1">Belum ada data mata kuliah</p>
        <p class="text-siakad-secondary dark:text-gray-400 text-sm">Tambahkan mata kuliah untuk memulai</p>
    </div>
    @endif
    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md animate-fade-in">
            <div class="px-6 py-4 border-b border-siakad-light dark:border-gray-700">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white">Tambah Mata Kuliah</h3>
            </div>
            <form action="{{ route('admin.mata-kuliah.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">Kode MK</label>
                        <input type="text" name="kode_mk" class="input-saas w-full px-4 py-2.5 text-sm font-mono dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Contoh: TI101, SI201, MK001" required>
                        <p class="text-xs text-siakad-secondary dark:text-gray-400 mt-1">Prefix: TI=Teknik Informatika, SI=Sistem Informasi, MK=Umum, dll</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">Nama Mata Kuliah</label>
                        <input type="text" name="nama_mk" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="Masukkan nama mata kuliah" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">SKS</label>
                            <input type="number" name="sks" min="1" max="6" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="3" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">Semester</label>
                            <input type="number" name="semester" min="1" max="8" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="1" required>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-siakad-light dark:border-gray-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white">Batal</button>
                    <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md animate-fade-in">
            <div class="px-6 py-4 border-b border-siakad-light dark:border-gray-700">
                <h3 class="text-lg font-semibold text-siakad-dark dark:text-white">Edit Mata Kuliah</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">Kode MK</label>
                        <input type="text" name="kode_mk" id="editKode" class="input-saas w-full px-4 py-2.5 text-sm font-mono dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">Nama Mata Kuliah</label>
                        <input type="text" name="nama_mk" id="editNama" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">SKS</label>
                            <input type="number" name="sks" id="editSks" min="1" max="6" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-siakad-dark dark:text-gray-300 mb-2">Semester</label>
                            <input type="number" name="semester" id="editSemester" min="1" max="8" class="input-saas w-full px-4 py-2.5 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-white" required>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-siakad-light dark:border-gray-700 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium dark:text-white">Batal</button>
                    <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editMK(id, kode, nama, sks, semester) {
            document.getElementById('editForm').action = `/admin/mata-kuliah/${id}`;
            document.getElementById('editKode').value = kode;
            document.getElementById('editNama').value = nama;
            document.getElementById('editSks').value = sks;
            document.getElementById('editSemester').value = semester;
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
