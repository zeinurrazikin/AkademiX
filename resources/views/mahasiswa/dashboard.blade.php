<x-mahasiswa-layout>
    <x-slot:title>Dashboard</x-slot:title>
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Mahasiswa</h1>
        <p class="text-gray-600">Informasi akademik Anda</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-admin.card 
            title="Total KRS" 
            value="{{ $stats['total_krs'] }}" 
            color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>'
        />
        
        <x-admin.card 
            title="KRS Disetujui" 
            value="{{ $stats['total_krs_disetujui'] }}" 
            color="green"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        />
        
        <x-admin.card 
            title="Total Nilai" 
            value="{{ $stats['total_nilai'] }}" 
            color="purple"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
        />
        
        <x-admin.card 
            title="IPK" 
            value="{{ number_format(auth()->user()->mahasiswa->ipk, 2) }}" 
            color="red"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Profil Mahasiswa</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">NIM</span>
                    <span class="text-sm text-gray-900">{{ auth()->user()->mahasiswa->nim }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Nama Lengkap</span>
                    <span class="text-sm text-gray-900">{{ auth()->user()->mahasiswa->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Email</span>
                    <span class="text-sm text-gray-900">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Tahun Masuk</span>
                    <span class="text-sm text-gray-900">{{ auth()->user()->mahasiswa->tahun_masuk }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-600">Status</span>
                    <span class="text-sm text-gray-900">{{ auth()->user()->mahasiswa->status_akademik }}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">KRS Terbaru</h3>
            @if($stats['total_krs_disetujui'] > 0)
                <div class="space-y-4">
                    @php
                        $latestKrs = auth()->user()->mahasiswa->krs()->where('status', 'disetujui')->latest()->first();
                    @endphp
                    @if($latestKrs)
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-sm font-medium text-gray-900">KRS {{ $latestKrs->periodeAkademik->nama_periode_lengkap }}</p>
                            <p class="text-sm text-gray-500">Status: <span class="font-semibold text-green-600">{{ $latestKrs->status_lengkap }}</span></p>
                            <p class="text-sm text-gray-500">Total SKS: {{ $latestKrs->total_sks }}</p>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada KRS disetujui</p>
            @endif
        </div>
    </div>
</x-mahasiswa-layout>