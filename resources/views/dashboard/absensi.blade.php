<x-dashboard.main title="Absensi">
    <div class="grid gap-5 md:gap-6">
        <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
            <span class="bg-blue-300 p-3 mr-4 text-gray-700 rounded-full"></span>
            <p class="text-sm font-medium capitalize text-gray-600">
                Tanggal
            </p>
            <span class="ml-auto text-right font-bold">{{ date('d-m-y') ?? '0' }}</span>
        </div>
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['hari', 'jam'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'hari' ? 'bg-blue-300' : '' }}
                    {{ $type == 'jam' ? 'bg-green-300' : '' }}
                    p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'hari' ? $hari ?? '-' : '' }}
                        {{ $type == 'jam' ? date('H:i') ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @if (Auth::user()->role === 'dosen')
        <!-- Data Display -->
        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-6">
            @foreach (['jumlah_mahasiswa_hadir', 'jumlah_mahasiswa_sakit', 'jumlah_mahasiswa_izin', 'jumlah_mahasiswa_alfa'] as $type)
                <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                    <span
                        class="
                {{ $type == 'jumlah_mahasiswa_hadir' ? 'bg-blue-300' : '' }}
                {{ $type == 'jumlah_mahasiswa_sakit' ? 'bg-green-300' : '' }}
                {{ $type == 'jumlah_mahasiswa_izin' ? 'bg-amber-300' : '' }}
                {{ $type == 'jumlah_mahasiswa_alfa' ? 'bg-rose-300' : '' }}
                p-3 mr-4 text-gray-700 rounded-full"></span>
                    <div>
                        <p class="text-sm font-medium capitalize text-gray-600">
                            {{ str_replace('_', ' ', $type) }}
                        </p>
                        <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                            {{ $type == 'jumlah_mahasiswa_hadir' ? $jumlah_mahasiswa_hadir ?? '-' : '' }}
                            {{ $type == 'jumlah_mahasiswa_sakit' ? $jumlah_mahasiswa_sakit ?? '-' : '' }}
                            {{ $type == 'jumlah_mahasiswa_izin' ? $jumlah_mahasiswa_izin ?? '-' : '' }}
                            {{ $type == 'jumlah_mahasiswa_alfa' ? $jumlah_mahasiswa_alfa ?? '-' : '' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table -->
        <div class="flex gap-5">
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        Daftar Absensi
                    </h1>
                    <p class="text-sm opacity-60">
                        Pantau kehadiran siswa dengan mudah dan akurat.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="flex flex-col sm:flex-row items-center gap-4 p-4">
                        <form method="GET" action="{{ route('absensi') }}" class="flex items-center gap-4 w-full">
                            <input type="date" id="date" name="tanggal" value="{{ $date }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 shadow-sm transition duration-300 ease-in-out">
                            <button type="submit"
                                class="btn btn-primary rounded-lg shadow-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 transition duration-300 ease-in-out">
                                Tampilkan
                            </button>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    @foreach (['No', 'Nama', 'waktu', 'lokasi', 'cek lokasi', 'foto', 'status', 'keterangan', 'pertemuan'] as $header)
                                        <th class="uppercase font-bold">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absen as $i => $item)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase">{{ $item->user->name }}</td>
                                        <td class="font-semibold uppercase">{{ $item->waktu }}</td>
                                        <td class="font-semibold uppercase">{{ $item->lokasi }}</td>
                                        <td class="font-semibold uppercase">
                                            <label for="lokasi_modal_{{ $item->id_absensi }}"
                                                class="w-full btn btn-accent">Lihat</label>
                                        </td>
                                        <td class="font-semibold uppercase">
                                            <label for="dokumentasi_modal_{{ $item->id_absensi }}"
                                                class="w-full btn btn-accent">Lihat</label>
                                        </td>
                                        @php
                                            $status = ucfirst($item->status);
                                            $labelClass = '';

                                            switch ($status) {
                                                case 'Hadir':
                                                    $labelClass =
                                                        'bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300';
                                                    break;
                                                case 'Izin':
                                                    $labelClass =
                                                        'bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300';
                                                    break;
                                                case 'Alpa':
                                                case 'Sakit':
                                                    $labelClass =
                                                        'bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300';
                                                    break;
                                                default:
                                                    $labelClass =
                                                        'bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300';
                                                    break;
                                            }
                                        @endphp
                                        <td class="font-semibold uppercase">
                                            <span class="{{ $labelClass }}">{{ $status }}</span>
                                        </td>
                                        <td class="font-semibold uppercase">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $item->keterangan ?? 'Tidak ada keterangan' }}</span>
                                        </td>
                                        <td class="font-semibold uppercase">Pertemuan {{ $item->pertemuan }}</td>
                                        <td>
                                            @if ($item->status === 'hadir')
                                                <x-lucide-check-circle class="text-green-500 w-6 h-6" />
                                            @elseif ($item->status === 'alpa' || $item->status === 'izin (ditolak)')
                                                <x-lucide-x-circle class="text-red-500 w-6 h-6" />
                                            @elseif ($item->status === 'izin (diterima)' || $item->status === 'sakit')
                                                <x-lucide-check-circle class="text-green-500 w-6 h-6" />
                                            @else
                                                <button class="btn btn-success btn-sm"
                                                    onclick="document.getElementById('terima_modal_{{ $item->id_absensi }}').showModal();">Terima</button>

                                                {{-- Modal Terima --}}
                                                <dialog id="terima_modal_{{ $item->id_absensi }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <h3 class="text-lg font-bold">Terima izin dari
                                                            {{ $item->user->name }}</h3>
                                                        <div class="mt-3">
                                                            <p>Apakah Anda yakin ingin menerima absensi dari
                                                                <strong>{{ $item->user->name }}</strong>?
                                                            </p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button"
                                                                onclick="document.getElementById('terima_modal_{{ $item->id_absensi }}').close()"
                                                                class="btn">Tutup</button>
                                                            <form
                                                                action="{{ route('terima_absensi', ['id_absensi' => $item->id_absensi]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('put')
                                                                <button type="submit"
                                                                    class="btn btn-success">Ya</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>

                                                |

                                                <button class="btn btn-danger btn-sm"
                                                    onclick="document.getElementById('tolak_modal_{{ $item->id_absensi }}').showModal();">Tolak</button>

                                                <dialog id="tolak_modal_{{ $item->id_absensi }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <h3 class="text-lg font-bold">Tolak izin dari
                                                            {{ $item->user->name }}</h3>
                                                        <div class="mt-3">
                                                            <p>Apakah Anda yakin ingin menolak absensi dari
                                                                <strong>{{ $item->user->name }}</strong>?
                                                            </p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button"
                                                                onclick="document.getElementById('tolak_modal_{{ $item->id_absensi }}').close()"
                                                                class="btn">Tutup</button>
                                                            <form
                                                                action="{{ route('tolak_absensi', ['id_absensi' => $item->id_absensi]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('put')
                                                                <button type="submit"
                                                                    class="btn btn-success">Ya</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-gray-700 opacity-60">Tidak ada
                                            mahasiswa mengambil absen</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-5">
            @foreach (['ambil_absen'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60">
                            {{ $item == 'ambil_absen' ? 'Gunakan fitur ini untuk mencatat kehadiran Anda dengan cepat dan mudah setiap kali menghadiri kelas.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus class="{{ $item == 'ambil_absen' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60" />
                </div>
            @endforeach
        </div>
        <div class="flex gap-5">
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        Daftar Absensi
                    </h1>
                    <p class="text-sm opacity-60">
                        Cek kehadiran Anda secara real-time dan pastikan semua sesi perkuliahan terpantau.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    @foreach (['No', 'Nama', 'tanggal', 'waktu', 'lokasi', 'foto', 'status', 'keterangan', 'pertemuan'] as $header)
                                        <th class="uppercase font-bold">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absen as $i => $item)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase">{{ $item->user->name }}</td>
                                        <td class="font-semibold uppercase">{{ $item->tanggal }}</td>
                                        <td class="font-semibold uppercase">{{ $item->waktu }}</td>
                                        <td class="font-semibold uppercase">{{ $item->lokasi }}</td>
                                        <td class="font-semibold uppercase">
                                            <label for="dokumentasi_modal_{{ $item->id_absensi }}"
                                                class="w-full btn btn-accent">Lihat</label>
                                                @php
                                                $status = ucfirst($item->status);
                                                $labelClass = '';
    
                                                switch ($status) {
                                                    case 'Hadir':
                                                        $labelClass =
                                                            'bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300';
                                                        break;
                                                    case 'Izin':
                                                        $labelClass =
                                                            'bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300';
                                                        break;
                                                    case 'Alpa':
                                                    case 'Sakit':
                                                        $labelClass =
                                                            'bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300';
                                                        break;
                                                    default:
                                                        $labelClass =
                                                            'bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300';
                                                        break;
                                                }
                                            @endphp
                                            <td class="font-semibold uppercase">
                                                <span class="{{ $labelClass }}">{{ $status }}</span>
                                            </td>
                                            <td class="font-semibold uppercase">
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">{{ $item->keterangan ?? 'Tidak ada keterangan' }}</span>
                                            </td>
                                            <td class="font-semibold uppercase">Pertemuan {{ $item->pertemuan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">Anda tidak mengisi absen di hari ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-dashboard.main>

<dialog id="ambil_absen_modal" class="modal modal-bottom sm:modal-middle">
    <form action="{{ route('store.absensi') }}" method="POST" enctype="multipart/form-data"
        class="modal-box bg-neutral">
        @csrf
        <h3 class="modal-title capitalize text-white">
            Ambil Absen
        </h3>
        <div class="modal-body">
            <div>
                <label for="nama"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                <input value="{{ Auth::user()->name }}" readonly id="nama" name="nama"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('nama')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            @php
                $date = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
                $tanggal = $date->format('Y-m-d');
                $waktu = $date->format('H:i');
            @endphp
            <div>
                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="waktu">Waktu</div>
                <input value="{{ $waktu }}" readonly id="waktu" name="waktu"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('waktu')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="tempat">Tempat</div>
                <input id="tempat" name="tempat" placeholder="Masukan tempat..." value="{{ old('tempat') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('tempat')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="status">Status</div>
                <select id="status" name="status"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">--- Pilih Status Kehadiran ---</option>
                    <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ old('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                    <option value="lainnya" {{ old('status') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="keterangan">Keterangan
                </div>
                <textarea name="keterangan" placeholder="Masukan keterangan jika ada..." id="keterangan" cols="30"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="bukti_absen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bukti
                    Absen</label>
                <div class="relative">
                    <input type="file" id="bukti_absen" name="bukti_absen" class="hidden"
                        onchange="updateLabelAndPreview()">
                    <label id="bukti_absen_label" for="bukti_absen"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg cursor-pointer focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center">
                        Choose File
                    </label>
                    <img id="preview" class="flex-shrink-0 mt-3 border rounded-lg w-full max-h-[300px] object-cover"
                        src="" alt="">
                </div>
                @error('bukti_absen')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="pertemuan"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pertemuan</label>
                <select id="pertemuan" name="pertemuan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @for ($i = 1; $i <= 16; $i++)
                        <option value="{{ $i }}" {{ old('pertemuan') == $i ? 'selected' : '' }}>
                            Pertemuan {{ $i }}</option>
                    @endfor
                </select>
                @error('pertemuan')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                @error('latitude')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                @error('longitude')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('ambil_absen_modal').close()"
                    class="btn">Tutup</button>
                <button type="submit" class="btn btn-success capitalize">Ambil Absen</button>
            </div>
        </div>
    </form>
</dialog>

@foreach ($absen as $i => $item)
    <!-- Lokasi Modal -->
    <input type="checkbox" id="lokasi_modal_{{ $item->id_absensi }}" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box" id="modal_box_{{ $item->id_absensi }}">
            <div class="modal-header flex justify-between items-center">
                <h3 class="text-lg font-bold">Lokasi</h3>
                <label for="lokasi_modal_{{ $item->id_absensi }}"
                    class="btn btn-sm btn-circle btn-ghost">&times;</label>
            </div>
            <div id="map_{{ $item->id_absensi }}" style="height: 400px;"></div>
            <div class="modal-action">
                <label for="lokasi_modal_{{ $item->id_absensi }}" class="btn">Tutup</label>
            </div>
        </div>
        <label class="modal-backdrop" for="lokasi_modal_{{ $item->id_absensi }}"></label>
    </div>

    <!-- Dokumentasi Modal -->
    <input type="checkbox" id="dokumentasi_modal_{{ $item->id_absensi }}" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box" id="modal_box_{{ $item->id_absensi }}">
            <div class="modal-header flex justify-between items-center">
                <h3 class="text-lg font-bold">Foto</h3>
                <label for="dokumentasi_modal_{{ $item->id_absensi }}"
                    class="btn btn-sm btn-circle btn-ghost">&times;</label>
            </div>
            <img src="{{ asset('storage/absensi/' . $item->foto) }}" class="border w-full h-auto"
                alt="{{ $item->user->name }}">
            <div class="modal-action">
                <label for="dokumentasi_modal_{{ $item->id_absensi }}" class="btn">Tutup</label>
            </div>
        </div>
        <label class="modal-backdrop" for="dokumentasi_modal_{{ $item->id_absensi }}"></label>
    </div>
@endforeach


<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        @foreach ($absen as $item)
            if (document.getElementById('lokasi_modal_{{ $item->id_absensi }}')) {
                document.getElementById('lokasi_modal_{{ $item->id_absensi }}').addEventListener('change',
                    function() {
                        if (this.checked) {
                            var map = L.map('map_{{ $item->id_absensi }}').setView([{{ $item->latitude }},
                                {{ $item->longitude }}
                            ], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            L.marker([{{ $item->latitude }}, {{ $item->longitude }}]).addTo(map)
                                .bindPopup('{{ $item->user->name }}')
                                .openPopup();
                        }
                    });
            }
        @endforeach
    });
</script>

@if (Auth::user()->role === 'mahasiswa')
    <script>
        window.addEventListener('load', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById('bukti_absen');
            const previewImage = document.getElementById('preview');
            const label = document.getElementById('bukti_absen_label');

            previewImage.style.display = 'none';

            fileInput.addEventListener('change', updateLabelAndPreview);

            previewImage.addEventListener('click', function() {
                previewImage.style.display = 'none';
                previewImage.src = '';
                fileInput.value = '';
                label.textContent = 'Choose File';
            });
        });

        function updateLabelAndPreview() {
            const fileInput = document.getElementById('bukti_absen');
            const file = fileInput.files[0];
            const previewImg = document.getElementById('preview');
            const label = document.getElementById('bukti_absen_label');

            if (file) {
                label.textContent = file.name;

                const fileType = file.type;
                const blob = URL.createObjectURL(file);

                if (fileType.startsWith('image/')) {
                    previewImg.style.display = 'block';
                    previewImg.src = blob;
                } else {
                    previewImg.style.display = 'none';
                    previewImg.src = '';
                }
            } else {
                label.textContent = 'Choose File';
                previewImg.style.display = 'none';
                previewImg.src = '';
            }
        }
    </script>
@endif
