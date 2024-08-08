<x-dashboard.main title="Dashboard">
    <div class="grid gap-5 md:gap-6">
        <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
            <span class="bg-blue-300 p-3 mr-4 text-gray-700 rounded-full"></span>
            <p class="text-sm font-medium capitalize text-gray-600">
                Waktu
            </p>
            <span class="ml-auto text-right">{{ date('d-m-y H:i') }}</span>
        </div>
    </div>
    @if (Auth::user()->role === 'dosen')
        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-6">
            @foreach (['jumlah_pembelajaran', 'jumlah_materi', 'jumlah_mahasiswa', 'jumlah_tugas'] as $type)
                <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                    <span
                        class="
                    {{ $type == 'jumlah_pembelajaran' ? 'bg-blue-300' : '' }}
                    {{ $type == 'jumlah_materi' ? 'bg-green-300' : '' }}
                    {{ $type == 'jumlah_mahasiswa' ? 'bg-rose-300' : '' }}
                    {{ $type == 'jumlah_tugas' ? 'bg-amber-300' : '' }}
                    p-3 mr-4 text-gray-700 rounded-full"></span>
                    <div>
                        <p class="text-sm font-medium capitalize text-gray-600">
                            {{ str_replace('_', ' ', $type) }}
                        </p>
                        <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                            {{ $type == 'jumlah_pembelajaran' ? $jumlah_pembelajaran ?? '0' : '' }}
                            {{ $type == 'jumlah_materi' ? $jumlah_materi ?? '0' : '' }}
                            {{ $type == 'jumlah_mahasiswa' ? $jumlah_mahasiswa ?? '0' : '' }}
                            {{ $type == 'jumlah_tugas' ? $jumlah_tugas ?? '0' : '' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
    <div class="grid sm:grid-cols-1 xl:grid-cols-3 gap-5 md:gap-6">
        @foreach (['jumlah_pembelajaran', 'jumlah_materi', 'jumlah_tugas'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                {{ $type == 'jumlah_pembelajaran' ? 'bg-blue-300' : '' }}
                {{ $type == 'jumlah_materi' ? 'bg-green-300' : '' }}
                {{ $type == 'jumlah_tugas' ? 'bg-amber-300' : '' }}
                p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'jumlah_pembelajaran' ? $jumlah_pembelajaran ?? '0' : '' }}
                        {{ $type == 'jumlah_materi' ? $jumlah_materi ?? '0' : '' }}
                        {{ $type == 'jumlah_tugas' ? $jumlah_tugas ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @endif
    <div class="flex flex-col xl:flex-row gap-5">
        @foreach (['pembelajaran', 'materi', 'tugas'] as $key => $jenis)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $jenis) }}
                        <span class="badge badge-xs sm:badge-sm uppercase badge-secondary">baru</span>
                    </h1>
                    <p class="text-sm opacity-60">Berdasarkan data pada {{ date('d-m-Y') }}</p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5">
                    @forelse (${$jenis} as $index => $data)
                        <div class="flex items-center gap-5 pt-3">
                            <h1>{{ $index + 1 }}</h1>
                            <div>
                                <h1 class="opacity-50 text-sm font-semibold">#Pertemuan {{ $data->pertemuan }}</h1>
                                <h1 class="font-semibold text-sm sm:text-[15px] hover:underline cursor-pointer">
                                    {{ $jenis === 'materi' ? $data->judul_materi : ucfirst($data->{'nama_' . ($jenis === 'pembelajaran' ? 'silabus' : $jenis)}) }}
                                </h1>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center gap-5 pt-3">
                            <h1>Tidak ada {{ $jenis }} terbaru.</h1>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>
