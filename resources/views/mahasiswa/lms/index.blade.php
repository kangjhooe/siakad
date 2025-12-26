<x-app-layout>
    <x-slot name="header">
        E-Learning
    </x-slot>

    <div class="mb-6">
        <h2 class="text-xl font-bold text-siakad-dark dark:text-white">Kelas Saya</h2>
        <p class="text-sm text-siakad-secondary dark:text-gray-400">Akses materi dan tugas dari kelas yang Anda ambil</p>
    </div>

    <!-- Search Filter -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" id="searchKelas" placeholder="Cari mata kuliah atau dosen..." class="input-saas w-full md:w-80 pl-10 pr-4 py-2.5 text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white" onkeyup="filterKelas()">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-siakad-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    @if($kelasList->isEmpty())
    <div class="card-saas p-8 text-center dark:bg-gray-800">
        <svg class="w-16 h-16 mx-auto mb-4 text-siakad-secondary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
        <p class="text-siakad-secondary dark:text-gray-400">Anda belum terdaftar di kelas apapun.</p>
        <a href="{{ route('mahasiswa.krs.index') }}" class="inline-block mt-3 text-siakad-primary hover:underline text-sm">Isi KRS →</a>
    </div>
    @else
    <div id="kelasGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($kelasList as $kelas)
        <div class="kelas-card card-saas dark:bg-gray-800 overflow-hidden hover:shadow-lg transition-shadow" data-search="{{ strtolower($kelas->mataKuliah->nama_mk . ' ' . $kelas->mataKuliah->kode_mk . ' ' . ($kelas->dosen->user->name ?? '')) }}">
            <div class="p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-siakad-primary to-siakad-dark rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        {{ $kelas->nama_kelas }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-siakad-dark dark:text-white truncate">{{ $kelas->mataKuliah->nama_mk }}</h3>
                        <p class="text-xs text-siakad-secondary dark:text-gray-400">{{ $kelas->mataKuliah->kode_mk }} • {{ $kelas->mataKuliah->sks }} SKS</p>
                    </div>
                </div>
                
                <p class="text-xs text-siakad-secondary dark:text-gray-400 mb-4">
                    Dosen: {{ $kelas->dosen->user->name ?? '-' }}
                </p>

                @if($kelas->pending_tugas > 0)
                <div class="mb-4 px-3 py-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                    <p class="text-xs text-amber-700 dark:text-amber-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $kelas->pending_tugas }} tugas belum dikumpulkan
                    </p>
                </div>
                @endif

                <div class="flex gap-2">
                    <a href="{{ route('mahasiswa.materi.index', $kelas->id) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium bg-siakad-light dark:bg-gray-700 text-siakad-dark dark:text-white rounded-lg hover:bg-siakad-light/80 dark:hover:bg-gray-600 transition">
                        Materi
                    </a>
                    <a href="{{ route('mahasiswa.tugas.index', $kelas->id) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium bg-siakad-primary text-white rounded-lg hover:bg-siakad-primary/90 transition">
                        Tugas
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- No Results Message -->
    <div id="noResults" class="hidden card-saas p-8 text-center dark:bg-gray-800">
        <svg class="w-12 h-12 mx-auto mb-3 text-siakad-secondary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <p class="text-siakad-secondary dark:text-gray-400">Tidak ada kelas yang ditemukan.</p>
    </div>
    @endif

    <script>
        function filterKelas() {
            const query = document.getElementById('searchKelas').value.toLowerCase();
            const cards = document.querySelectorAll('.kelas-card');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const searchText = card.dataset.search;
                if (searchText.includes(query)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0);
            }
        }
    </script>
</x-app-layout>

