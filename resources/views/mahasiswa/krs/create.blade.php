<x-mahasiswa-layout>
    <x-slot:title>Buat KRS</x-slot:title>
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Buat Kartu Rencana Studi (KRS)</h1>
        <p class="text-gray-600">Pilih mata kuliah untuk semester {{ $periodeAktif->nama_periode_lengkap }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-blue-800">Periode Aktif</p>
                <p class="text-lg font-semibold text-blue-900">{{ $periodeAktif->nama_periode_lengkap }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-green-800">IPK Terakhir</p>
                <p class="text-lg font-semibold text-green-900">{{ number_format(auth()->user()->mahasiswa->ipk, 2) }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-purple-800">Batas SKS</p>
                <p class="text-lg font-semibold text-purple-900">
                    @php
                        $batasSks = 16;
                        if (auth()->user()->mahasiswa->ipk >= 3.5) $batasSks = 24;
                        elseif (auth()->user()->mahasiswa->ipk >= 3.0) $batasSks = 22;
                        elseif (auth()->user()->mahasiswa->ipk >= 2.5) $batasSks = 20;
                        elseif (auth()->user()->mahasiswa->ipk >= 2.0) $batasSks = 18;
                    @endphp
                    {{ $batasSks }} SKS
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('mahasiswa.krs.store') }}">
            @csrf
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode MK
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama MK
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                SKS
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dosen
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jam
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ruang
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jadwal as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="jadwal_id[]" value="{{ $item->id }}" class="jadwal-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->mataKuliah->kode_mk }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $item->mataKuliah->nama_mk }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->mataKuliah->total_sks }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $item->dosen->getNamaLengkapWithGelarAttribute() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->hari }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->jam_mulai }} - {{ $item->jam_selesai }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->ruang->kode_ruang }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada jadwal tersedia untuk periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('mahasiswa.krs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan KRS
                </button>
            </div>
        </form>
    </div>
</x-mahasiswa-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.jadwal-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
        });
    });
});
</script>