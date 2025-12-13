<x-app-layout>
    <x-slot name="header">
        Data Mahasiswa
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-siakad-secondary dark:text-gray-400">Kelola data mahasiswa dalam sistem</p>
        </div>
        <form method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..." class="input-saas px-4 py-2 text-sm w-full sm:w-64 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
            
            <select name="fakultas_id" id="filterFakultas" class="input-saas px-4 py-2 text-sm w-full sm:w-48 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <option value="">Fakultas</option>
                @foreach($fakultasList as $f)
                <option value="{{ $f->id }}" {{ request('fakultas_id') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                @endforeach
            </select>

            <select name="prodi_id" id="filterProdi" class="input-saas px-4 py-2 text-sm w-full sm:w-48 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <option value="">Prodi</option>
                @foreach($prodiList as $p)
                <option value="{{ $p->id }}" data-fakultas-id="{{ $p->fakultas_id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>

            <select name="angkatan" class="input-saas px-4 py-2 text-sm w-full sm:w-32 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <option value="">Angkatan</option>
                @foreach($angkatanList as $angkatan)
                <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>{{ $angkatan }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">Filter</button>
            <a href="{{ route('admin.mahasiswa.export', request()->all()) }}" target="_blank" class="btn-ghost-saas px-4 py-2 dark:text-white rounded-lg text-sm font-medium w-full sm:w-auto text-center border border-siakad-light dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export
            </a>
            @if(request()->anyFilled(['search', 'fakultas_id', 'prodi_id', 'angkatan']))
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn-ghost-saas px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto text-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Script for Dynamic Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fakultasSelect = document.getElementById('filterFakultas');
            const prodiSelect = document.getElementById('filterProdi');
            const prodiOptions = Array.from(prodiSelect.options);

            function updateProdiOptions() {
                const selectedFakultasId = fakultasSelect.value;
                const currentProdiValue = prodiSelect.value;
                let isCurrentProdiValid = false;

                // First option (Semua Prodi) always visible
                prodiSelect.innerHTML = '';
                prodiSelect.appendChild(prodiOptions[0]);

                prodiOptions.slice(1).forEach(option => {
                    if (!selectedFakultasId || option.dataset.fakultasId === selectedFakultasId) {
                        prodiSelect.appendChild(option);
                        if (option.value === currentProdiValue) {
                            isCurrentProdiValid = true;
                        }
                    }
                });

                // Reset prodi selection if current selection is no longer valid
                if (currentProdiValue && !isCurrentProdiValid) {
                    prodiSelect.value = '';
                } else {
                    prodiSelect.value = currentProdiValue;
                }
            }

            fakultasSelect.addEventListener('change', updateProdiOptions);
            
            // Initial run to set correct state on page load (if filtering is active)
            updateProdiOptions();
        });
    </script>

    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                        
                        <!-- Sortable: Mahasiswa (Name) -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.mahasiswa.index', array_merge(request()->all(), ['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                Mahasiswa
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'name' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'name' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'name' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: NIM -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.mahasiswa.index', array_merge(request()->all(), ['sort' => 'nim', 'order' => request('sort') == 'nim' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                NIM
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'nim' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'nim' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'nim' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Prodi -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.mahasiswa.index', array_merge(request()->all(), ['sort' => 'prodi', 'order' => request('sort') == 'prodi' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                Prodi
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'prodi' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'prodi' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'prodi' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Angkatan -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.mahasiswa.index', array_merge(request()->all(), ['sort' => 'angkatan', 'order' => request('sort') == 'angkatan' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                Angkatan
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'angkatan' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'angkatan' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'angkatan' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">IPK</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @forelse($mahasiswa as $index => $m)
                    <tr class="border-b border-siakad-light/50 dark:border-gray-700/50">
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $mahasiswa->firstItem() + $index }}</td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-siakad-primary dark:bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($m->user->name ?? '-', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ $m->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-mono text-siakad-secondary dark:text-gray-400">{{ $m->nim }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm text-siakad-secondary dark:text-gray-400">{{ $m->prodi->nama ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-secondary/10 text-siakad-secondary dark:bg-gray-700 dark:text-gray-300 rounded-full">{{ $m->angkatan }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-semibold text-siakad-primary dark:text-blue-400">{{ number_format($m->ipk ?? 0, 2) }}</span>
                        </td>
                        <td class="py-4 px-5">
                            @if($m->status === 'aktif')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 rounded-full border dark:border-emerald-500/20">Aktif</span>
                            @elseif($m->status === 'cuti')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 rounded-full border dark:border-amber-500/20">Cuti</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-gray-700 dark:text-gray-300 rounded-full border dark:border-gray-500/20">{{ ucfirst($m->status ?? 'Aktif') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-right">
                            <a href="{{ route('admin.mahasiswa.show', $m) }}" class="inline-flex p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="text-siakad-secondary dark:text-gray-400 text-sm">Tidak ada data mahasiswa</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card List -->
    <div class="md:hidden space-y-4">
        @forelse($mahasiswa as $m)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-siakad-primary dark:bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr($m->user->name ?? '-', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-siakad-dark dark:text-white">{{ $m->user->name ?? '-' }}</h4>
                        <p class="text-xs text-siakad-secondary dark:text-gray-400 font-mono">{{ $m->nim }}</p>
                    </div>
                </div>
                @if($m->status === 'aktif')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 rounded-full">Aktif</span>
                @elseif($m->status === 'cuti')
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 rounded-full">Cuti</span>
                @else
                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 rounded-full">{{ ucfirst($m->status ?? 'Aktif') }}</span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm text-siakad-secondary dark:text-gray-400 mb-4">
                <div>
                    <span class="block text-xs text-gray-400">Prodi</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $m->prodi->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400">Angkatan</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $m->angkatan }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400">IPK</span>
                    <span class="font-semibold text-siakad-primary dark:text-blue-400">{{ number_format($m->ipk ?? 0, 2) }}</span>
                </div>
            </div>

            <a href="{{ route('admin.mahasiswa.show', $m) }}" class="flex items-center justify-center w-full py-2 bg-siakad-light dark:bg-gray-700 text-siakad-dark dark:text-white font-medium rounded-lg hover:bg-gray-200 transition text-sm">
                Detail Mahasiswa
            </a>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-siakad-secondary dark:text-gray-400">Tidak ada data mahasiswa</p>
        </div>
        @endforelse
    </div>
        @if($mahasiswa->hasPages())
        <div class="px-5 py-4 border-t border-siakad-light dark:border-gray-700">
            {{ $mahasiswa->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
