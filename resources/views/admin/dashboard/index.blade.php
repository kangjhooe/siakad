<x-app-layout>
    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark dark:text-white">{{ $stats['fakultas'] }}</p>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">Fakultas</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-secondary/10 dark:bg-gray-700/50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark dark:text-white">{{ $stats['prodi'] }}</p>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">Prodi</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark dark:text-white">{{ $stats['mahasiswa'] }}</p>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">Mahasiswa</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-dark/10 dark:bg-gray-700/50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-dark dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark dark:text-white">{{ $stats['dosen'] }}</p>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">Dosen</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-secondary/10 dark:bg-gray-700/50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark dark:text-white">{{ $stats['mata_kuliah'] }}</p>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">Mata Kuliah</p>
                </div>
            </div>
        </div>

        <div class="card-saas p-5 dark:bg-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-siakad-dark dark:text-white">{{ $stats['kelas'] }}</p>
                    <p class="text-xs text-siakad-secondary dark:text-gray-400">Kelas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- KRS Status -->
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="font-semibold text-siakad-dark dark:text-white mb-4">Status KRS</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <span class="text-sm text-siakad-dark dark:text-gray-200">Approved</span>
                    </div>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $krsStatus['approved'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-50 dark:bg-amber-900/10 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                        <span class="text-sm text-siakad-dark dark:text-gray-200">Pending</span>
                    </div>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $krsStatus['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/10 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span class="text-sm text-siakad-dark dark:text-gray-200">Rejected</span>
                    </div>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $krsStatus['rejected'] }}</span>
                </div>
				<div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-gray-700/30 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                        <span class="text-sm text-siakad-dark dark:text-gray-200">Draft</span>
                    </div>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $krsStatus['draft'] }}</span>
                </div>
            </div>
        </div>

        <!-- Grade Distribution -->
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="font-semibold text-siakad-dark dark:text-white mb-4">Distribusi Nilai</h3>
            <div class="h-48">
                <canvas id="gradeChart"></canvas>
            </div>
        </div>

        <!-- Students per Prodi -->
        <div class="card-saas p-6 dark:bg-gray-800">
            <h3 class="font-semibold text-siakad-dark dark:text-white mb-4">Mahasiswa per Prodi</h3>
            <div class="space-y-3 max-h-48 overflow-y-auto">
                @foreach($prodiStats as $prodi)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-siakad-secondary dark:text-gray-400 truncate flex-1 mr-2">{{ $prodi->nama }}</span>
                    <span class="text-sm font-semibold text-siakad-dark dark:text-white">{{ $prodi->mahasiswa_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card-saas p-6 dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-siakad-dark dark:text-white">Aktivitas KRS Terbaru</h3>
            <a href="{{ url('admin/krs-approval') }}" class="text-sm text-siakad-primary dark:text-blue-400 hover:underline font-medium">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full table-saas">
                <thead>
                    <tr class="border-b border-siakad-light dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Mahasiswa</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">NIM</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Prodi</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-siakad-secondary dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentKrs as $krs)
                    <tr class="border-b border-siakad-light/50 dark:border-gray-700/50">
                        <td class="py-3 px-4">
                            <span class="text-sm font-medium text-siakad-dark dark:text-white">{{ $krs->mahasiswa->user->name ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm text-siakad-secondary dark:text-gray-400 font-mono">{{ $krs->mahasiswa->nim ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm text-siakad-secondary dark:text-gray-400">{{ $krs->mahasiswa->prodi->nama ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($krs->status === 'approved')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 rounded-full border dark:border-emerald-500/20">Approved</span>
                            @elseif($krs->status === 'pending')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 rounded-full border dark:border-amber-500/20">Pending</span>
                            @elseif($krs->status === 'rejected')
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-full border dark:border-red-500/20">Rejected</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-gray-700 dark:text-gray-300 rounded-full border dark:border-gray-500/20">Draft</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm text-siakad-secondary dark:text-gray-400">{{ $krs->updated_at->format('d M Y') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-siakad-secondary dark:text-gray-500 text-sm">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ url('admin/fakultas') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-siakad-primary/20 dark:group-hover:bg-blue-500/30 transition">
                <svg class="w-4 h-4 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">Tambah Fakultas</span>
        </a>
        <a href="{{ url('admin/prodi') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-secondary/10 dark:bg-gray-700/50 rounded-lg flex items-center justify-center group-hover:bg-siakad-secondary/20 dark:group-hover:bg-gray-600 transition">
                <svg class="w-4 h-4 text-siakad-secondary dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">Tambah Prodi</span>
        </a>
        <a href="{{ url('admin/mahasiswa') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-siakad-primary/10 dark:bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-siakad-primary/20 dark:group-hover:bg-blue-500/30 transition">
                <svg class="w-4 h-4 text-siakad-primary dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">Kelola Mahasiswa</span>
        </a>
        <a href="{{ url('admin/krs-approval') }}" class="card-saas p-4 hover:border-siakad-primary/30 dark:hover:border-blue-500/30 group flex items-center gap-3 dark:bg-gray-800">
            <div class="w-9 h-9 bg-amber-100 dark:bg-amber-900/20 rounded-lg flex items-center justify-center group-hover:bg-amber-200 dark:group-hover:bg-amber-900/40 transition">
                <svg class="w-4 h-4 text-amber-700 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <span class="text-sm font-medium text-siakad-dark dark:text-white">Approval KRS</span>
        </a>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const siakadPrimary = '#234C6A';
        const siakadSecondary = '#456882';
        const siakadDark = '#1B3C53';
        
        // Grade Chart
        const gradeData = @json($gradeDistribution);
        const gradeCtx = document.getElementById('gradeChart').getContext('2d');
        new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(gradeData),
                datasets: [{
                    data: Object.values(gradeData),
                    backgroundColor: [
                        siakadPrimary,
                        siakadSecondary,
                        siakadDark,
                        '#86c5e0',
                        '#b9dded',
                        '#dceef6',
                        '#E3E3E3'
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 12, padding: 12, font: { size: 11 } }
                    }
                },
                cutout: '60%'
            }
        });
    </script>
    @endpush
</x-app-layout>
