<x-dashboard.main title="Dashboard">
    <div class="grid gap-5 md:gap-6">
        <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
            <span class="bg-blue-300 p-3 mr-4 text-gray-700 rounded-full"></span>
            <p class="text-sm font-medium capitalize text-gray-600">
                Waktu
            </p>
        </div>
    </div>
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
                    {{-- <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'waktu' ? '0' : '' }}
                        {{ ucfirst($type == 'role' ? $role : '') }}
                        {{ $type == 'terakhir_login' ? $login : '' }}
                        {{ $type == 'register' ? $register : '' }}
                    </p> --}}
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>
