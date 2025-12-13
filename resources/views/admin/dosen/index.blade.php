<x-app-layout>
    <x-slot name="header">
        Data Dosen
    </x-slot>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-siakad-secondary dark:text-gray-400">Kelola data dosen dalam sistem</p>
        </div>
        <form method="GET" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIDN..." class="input-saas px-4 py-2 text-sm w-64 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
            <select name="prodi" class="input-saas px-4 py-2 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300">
                <option value="">Semua Prodi</option>
                @foreach($prodiList as $p)
                <option value="{{ $p->id }}" {{ request('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary-saas px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
        </form>
    </div>

    <!-- Table Card -->
    <!-- Table Card (Desktop) -->
    <div class="hidden md:block card-saas overflow-hidden dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="bg-siakad-light/30 dark:bg-gray-900 border-b border-siakad-light dark:border-gray-700">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                        
                        <!-- Sortable: Dosen (Name) -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.dosen.index', array_merge(request()->all(), ['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                Dosen
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'name' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'name' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'name' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: NIDN -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.dosen.index', array_merge(request()->all(), ['sort' => 'nidn', 'order' => request('sort') == 'nidn' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                NIDN
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'nidn' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'nidn' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'nidn' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <!-- Sortable: Prodi -->
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('admin.dosen.index', array_merge(request()->all(), ['sort' => 'prodi', 'order' => request('sort') == 'prodi' && request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 hover:text-siakad-primary transition">
                                Prodi
                                <span class="flex flex-col text-[10px] leading-none {{ request('sort') == 'prodi' ? 'text-siakad-primary' : 'text-gray-300' }}">
                                    <i class="opacity-{{ request('sort') == 'prodi' && request('order') == 'asc' ? '100' : '40' }}">▲</i>
                                    <i class="opacity-{{ request('sort') == 'prodi' && request('order') == 'desc' ? '100' : '40' }}">▼</i>
                                </span>
                            </a>
                        </th>

                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Kelas Diampu</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Mhs Bimbingan</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-siakad-light dark:divide-gray-700">
                    @forelse($dosen as $index => $d)
                    <tr class="border-b border-siakad-light/50 dark:border-gray-700/50">
                        <td class="py-4 px-5 text-sm text-siakad-secondary dark:text-gray-400">{{ $dosen->firstItem() + $index }}</td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-siakad-secondary dark:bg-gray-700 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($d->user->name ?? '-', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ $d->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-mono text-siakad-secondary dark:text-gray-400">{{ $d->nidn }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm text-siakad-secondary dark:text-gray-400">{{ $d->prodi->nama ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-primary/10 text-siakad-primary dark:bg-blue-500/10 dark:text-blue-400 rounded-full">{{ $d->kelas_count ?? $d->kelas->count() }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-siakad-secondary/10 text-siakad-secondary dark:bg-gray-700 dark:text-gray-300 rounded-full">{{ $d->mahasiswa_bimbingan_count ?? $d->mahasiswaBimbingan->count() }}</span>
                        </td>
                        <td class="py-4 px-5 text-right">
                            <a href="{{ route('admin.dosen.show', $d) }}" class="inline-flex p-2 text-siakad-secondary hover:text-siakad-primary hover:bg-siakad-primary/10 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-siakad-light/50 dark:bg-gray-700/50 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <p class="text-siakad-secondary dark:text-gray-400 text-sm">Tidak ada data dosen</p>
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
        @forelse($dosen as $d)
        <div class="card-saas p-4 dark:bg-gray-800">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-siakad-secondary dark:bg-gray-700 flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr($d->user->name ?? '-', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-siakad-dark dark:text-white">{{ $d->user->name ?? '-' }}</h4>
                        <p class="text-xs text-siakad-secondary dark:text-gray-400 font-mono">{{ $d->nidn }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm text-siakad-secondary dark:text-gray-400 mb-4">
                <div class="col-span-2">
                    <span class="block text-xs text-gray-400">Prodi</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $d->prodi->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400">Kelas Diampu</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $d->kelas_count ?? $d->kelas->count() }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400">Mhs Bimbingan</span>
                    <span class="font-medium text-siakad-dark dark:text-gray-200">{{ $d->mahasiswa_bimbingan_count ?? $d->mahasiswaBimbingan->count() }}</span>
                </div>
            </div>

            <a href="{{ route('admin.dosen.show', $d) }}" class="flex items-center justify-center w-full py-2 bg-siakad-light dark:bg-gray-700 text-siakad-dark dark:text-white font-medium rounded-lg hover:bg-gray-200 transition text-sm">
                Detail Dosen
            </a>
        </div>
        @empty
        <div class="card-saas p-8 text-center">
            <p class="text-siakad-secondary dark:text-gray-400">Tidak ada data dosen</p>
        </div>
        @endforelse
    </div>

    @if($dosen->hasPages())
    <div class="card-saas px-5 py-4 border-t border-siakad-light dark:border-gray-700 dark:bg-gray-800 mt-4 md:mt-0">
        {{ $dosen->links() }}
    </div>
    @endif
</x-app-layout>
