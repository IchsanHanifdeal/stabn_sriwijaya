<x-dashboard.main title="Tugas">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['tugas_terbaru', 'terakhir_upload'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                {{ $type == 'tugas_terbaru' ? 'bg-blue-300' : '' }}
                {{ $type == 'terakhir_upload' ? 'bg-green-300' : '' }}
                p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'tugas_terbaru' ? $tugas_terbaru->judul_tugas ?? '-' : '' }}
                        {{ $type == 'terakhir_upload' ? $terakhir_upload->updated_at ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @if (Auth::user()->role === 'dosen')
        <div class="flex flex-col lg:flex-row gap-5">
            @foreach (['tambah_tugas'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60">
                            {{ $item == 'tambah_tugas' ? 'Tambah tugas memungkinkan dosen untuk menambah materi baru dengan topik dan tujuan yang jelas, memperkaya pembelajaran mahasiswa.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus class="{{ $item == 'tambah_tugas' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60" />
                </div>
            @endforeach
        </div>
    @endif
    <div class="flex gap-5">
        @foreach (['Daftar_tugas'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Daftar tugas ini memudahkan dosen untuk mengelola dan menyampaikan tugas-tugas kepada mahasiswa,
                        memastikan informasi tersampaikan dengan baik.
                    </p>
                </div>
                <div class="w-full px-5 sm:px-7 bg-zinc-50">
                    <form action="{{ route('tugas') }}" method="GET"
                        class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex-grow">
                            <select id="mata_kuliah" name="mata_kuliah"
                                class="input input-sm shadow-md w-full bg-zinc-100">
                                <option value="">--- Filter Berdasarkan Mata Kuliah ---</option>
                                @forelse ($matakuliah as $matkul)
                                    <option value="{{ $matkul->id_matakuliah }}"
                                        {{ request('mata_kuliah') == $matkul->id_matakuliah ? 'selected' : '' }}>
                                        {{ $matkul->mata_kuliah }}
                                    </option>
                                @empty
                                    <option value="">Tidak ada mata kuliah terdaftar</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    @foreach (['No', 'judul tugas', 'mata kuliah', 'pertemuan', 'deskripsi', 'lampiran'] as $header)
                                        <th class="uppercase font-bold">{{ $header }}</th>
                                    @endforeach
                                    @if (Auth::user()->role === 'dosen')
                                        <th class="uppercase font-bold">Pengumpulan</th>
                                    @else
                                        <th class="uppercase font-bold">Komentar</th>
                                        <th class="uppercase font-bold">Nilai</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tugas as $i => $task)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase">{{ $task->judul_tugas }}</td>
                                        <td class="font-semibold uppercase">{{ $task->matakuliah->mata_kuliah }}</td>
                                        <td class="font-semibold uppercase">Pertemuan {{ $task->pertemuan }}</td>
                                        <td class="font-semibold uppercase">{{ $task->deskripsi }}</td>
                                        <td class="font-semibold uppercase">
                                            <label for="lihat_modal_{{ $task->id_tugas }}"
                                                class="w-full btn btn-accent flex items-center justify-center gap-2">
                                                <span>Lihat</span>
                                            </label>
                                        </td>
                                        @if (Auth::user()->role === 'mahasiswa')
                                            @php
                                                $nilaiItem = $nilai->get($task->id_tugas);
                                                $hasSubmitted = $nilai->has($task->id_tugas);
                                                $isGraded = $nilaiItem ? $nilaiItem->nilai !== null : false;
                                            @endphp
                                            <td class="font-semibold">
                                                {{ $nilaiItem->komentar ?? 'Tidak ada komentar' }}
                                            </td>
                                            <td class="font-semibold uppercase">
                                                {{ $nilaiItem->nilai ?? 'Belum Dinilai' }}
                                            </td>
                                            @if (!$hasSubmitted)
                                                <td>
                                                    <label for="kumpul_tugas_modal_{{ $task->id_tugas }}"
                                                        class="w-full btn btn-accent">Kumpul Tugas</label>
                                                </td>
                                            @else
                                                <td>
                                                    <form id="form_{{ $task->id_tugas }}"
                                                        action="{{ route('hapus_nilai', ['id_tugas' => $task->id_tugas, 'id_mahasiswa' => $log_mahasiswa]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-error"
                                                            data-confirm="form_{{ $task->id_tugas }}"
                                                            {{ $isGraded ? 'disabled' : '' }}>
                                                            Tarik Tugas
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        @elseif (Auth::user()->role === 'dosen')
                                            <td class="font-semibold uppercase">
                                                <button class="w-full btn btn-primary"
                                                    onclick="document.getElementById('pengumpulan_modal_{{ $task->id_tugas }}').showModal();">
                                                    <x-lucide-combine class="size-5" />
                                                </button>
                                            </td>
                                            <td class="flex items-center">
                                                <button class="w-full btn btn-error"
                                                    onclick="document.getElementById('delete_modal_{{ $task->id_tugas }}').showModal();">
                                                    <x-lucide-trash class="size-5" />
                                                </button>
                                            </td>
                                        @else
                                            <td>Undefined</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-500">Tidak ada Tugas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</x-dashboard.main>
<!-- Alert Modal -->
<div id="alert_modal" role="alert" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-[#e2e6da] rounded-lg shadow-lg p-6 max-w-sm mx-auto">
        <div class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="stroke-info h-6 w-6 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="ml-4">
                <h3 class="text-lg font-semibold">Konfirmasi Penarikan</h3>
                <p class="mt-2">Apakah Anda yakin ingin menarik tugas ini?</p>
            </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button id="deny_button" class="btn btn-sm">Tidak</button>
            <button id="accept_button" class="btn btn-sm btn-primary">Ya</button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertModal = document.getElementById('alert_modal');
        const denyButton = document.getElementById('deny_button');
        const acceptButton = document.getElementById('accept_button');
        let formToSubmit = null;

        document.querySelectorAll('button[data-confirm]').forEach(button => {
            button.addEventListener('click', function() {
                formToSubmit = document.getElementById(this.getAttribute('data-confirm'));
                alertModal.classList.remove('hidden');
            });
        });

        denyButton.addEventListener('click', function() {
            alertModal.classList.add('hidden');
        });

        acceptButton.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
            alertModal.classList.add('hidden');
        });
    });
</script>

@foreach ($tugas as $i => $item)
    <input type="checkbox" id="kumpul_tugas_modal_{{ $item->id_tugas }}" class="modal-toggle" />
    <div class="modal" role="dialog" id="modal_{{ $item->id_tugas }}">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Kumpul Tugas</h3>
            <div class="flex flex-col w-full gap-3 !h-full mt-3 rounded-lg overflow-hidden">
                <div id="preview_area_{{ $item->id_tugas }}" class="w-full">
                    <img id="file_preview_{{ $item->id_tugas }}" class="border size-full hidden" alt="File Preview"
                        onclick="clearFile({{ $item->id_tugas }})">
                    <iframe id="pdf_preview_{{ $item->id_tugas }}" class="border size-full hidden"
                        style="width: 100%; height: 400px;" frameborder="0"
                        onclick="clearFile({{ $item->id_tugas }})"></iframe>
                    <video id="video_preview_{{ $item->id_tugas }}" class="border size-full hidden" controls
                        style="width: 100%; height: auto;" onclick="clearFile({{ $item->id_tugas }})">
                        Your browser does not support the video tag.
                    </video>
                    <img id="default_preview_{{ $item->id_tugas }}" src="https://ui-avatars.com/api/?name=Null"
                        class="border size-full" alt="Pengumuman Default">
                </div>

                <form action="{{ route('kumpul_tugas', $item->id_tugas ?? 0) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" accept="image/*,application/pdf,video/*"
                        id="file_input_{{ $item->id_tugas }}" class="hidden" name="file">
                    @error('file')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                    @if (auth()->user()->role == 'mahasiswa')
                        <label for="file_input_{{ $item->id_tugas }}"
                            class="w-full cursor-pointer btn btn-sm btn-accent">Pilih File</label>
                        <button type="submit"
                            class="w-full cursor-pointer btn btn-sm btn-accent mt-3">Kumpulkan</button>
                    @endif
                </form>
            </div>
        </div>
        <label class="modal-backdrop" for="kumpul_tugas_modal_{{ $item->id_tugas }}"></label>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let openModalId = null;

            function updatePreview(id_tugas) {
                const fileInput = document.getElementById(`file_input_${id_tugas}`);
                const filePreview = document.getElementById(`file_preview_${id_tugas}`);
                const pdfPreview = document.getElementById(`pdf_preview_${id_tugas}`);
                const videoPreview = document.getElementById(`video_preview_${id_tugas}`);
                const defaultPreview = document.getElementById(`default_preview_${id_tugas}`);

                function clearFile() {
                    fileInput.value = '';
                    filePreview.classList.add('hidden');
                    pdfPreview.classList.add('hidden');
                    videoPreview.classList.add('hidden');
                    defaultPreview.classList.remove('hidden');
                }

                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    const fileURL = file ? URL.createObjectURL(file) : null;

                    filePreview.classList.add('hidden');
                    pdfPreview.classList.add('hidden');
                    videoPreview.classList.add('hidden');
                    defaultPreview.classList.add('hidden');

                    if (file) {
                        const fileType = file.type;

                        if (fileType.startsWith('image/')) {
                            filePreview.src = fileURL;
                            filePreview.classList.remove('hidden');
                        } else if (fileType === 'application/pdf') {
                            pdfPreview.src = fileURL;
                            pdfPreview.classList.remove('hidden');
                        } else if (fileType.startsWith('video/')) {
                            videoPreview.src = fileURL;
                            videoPreview.classList.remove('hidden');
                        }
                    }
                });

                filePreview.addEventListener('click', clearFile);
                pdfPreview.addEventListener('click', clearFile);
                videoPreview.addEventListener('click', clearFile);
            }

            document.querySelectorAll('label[for^="kumpul_tugas_modal_"]').forEach(label => {
                label.addEventListener('click', function() {
                    const modalId = `modal_${this.getAttribute('for').split('_').pop()}`;
                    if (openModalId && openModalId !== modalId) {
                        document.getElementById(openModalId).querySelector('.modal-toggle')
                            .checked = false;
                    }
                    openModalId = modalId;
                    updatePreview(this.getAttribute('for').split('_').pop());
                });
            });
        });
    </script>
@endforeach

@foreach ($tugas as $i => $item)
    <input type="checkbox" id="lihat_modal_{{ $item->id_tugas }}" class="modal-toggle" />
    <div class="modal" role="dialog">
        <div class="modal-box" id="modal_box_{{ $item->id_tugas }}">
            <div class="modal-header flex justify-between items-center">
                <h3 class="text-lg font-bold">Lampiran Tugas</h3>
                <label for="lihat_modal_{{ $item->id_tugas }}"
                    class="btn btn-sm btn-circle btn-ghost">&times;</label>
            </div>
            <div class="modal-body">
                @if ($item->lampiran_tugas)
                    @php
                        $fileExtension = pathinfo($item->lampiran_tugas, PATHINFO_EXTENSION);
                    @endphp

                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $item->lampiran_tugas) }}" alt="Image"
                            class="w-full h-auto">
                    @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                        <video controls class="w-full h-auto">
                            <source src="{{ asset('storage/' . $item->lampiran_tugas) }}"
                                type="video/{{ $fileExtension }}">
                            Your browser does not support the video tag.
                        </video>
                    @elseif ($fileExtension === 'pdf')
                        <iframe src="{{ asset('storage/' . $item->lampiran_tugas) }}" class="w-full h-96"
                            frameborder="0"></iframe>
                    @else
                        <p>Tipe file tidak di dukung.</p>
                    @endif
                @else
                    <p>tidak ada lampiran.</p>
                @endif
            </div>
        </div>
    </div>

    <dialog id="delete_modal_{{ $item->id_tugas }}" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Hapus {{ $item->judul_materi }}</h3>
            <div class="mt-3">
                <p>Apakah Anda yakin ingin menghapus tugas <strong
                        class="text-red-500">{{ $item->judul_tugas }}?.</strong> <strong>Tindakan ini tidak dapat di
                        urungkan.</strong>
                </p>
            </div>
            <div class="modal-action">
                <button type="button"
                    onclick="document.getElementById('delete_modal_{{ $item->id_tugas }}').close()"
                    class="btn">Batal</button>
                <form action="{{ route('hapus.tugas', $item->id_tugas) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </dialog>
@endforeach

@foreach ($tugas as $item)
    <!-- Pengumpulan Modal -->
    <dialog id="pengumpulan_modal_{{ $item->id_tugas }}" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="modal-title capitalize">
                Pengumpulan tugas {{ $item->judul_tugas }}
            </h3>
            <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                @foreach (['No', 'Nama Mahasiswa', 'Tanggal Pengumpulan', 'File', 'Komentar', 'Keterangan', 'Nilai'] as $head)
                                    <th class="uppercase font-bold">{{ $head }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($item->nilai as $n => $list)
                                <tr>
                                    <td>{{ $n + 1 }}</td>
                                    <td class="font-semibold">{{ ucfirst($list->mahasiswa->name ?? '-') }}</td>
                                    <td class="font-semibold uppercase">{{ $list->tanggal_pengumpulan }}</td>
                                    <td class="font-semibold uppercase">
                                        <a href="{{ asset('storage/' . $list->file) }}" target="_blank"
                                            class="w-full btn btn-accent flex items-center justify-center gap-2">
                                            <x-lucide-scan-eye class="size-5" />
                                        </a>
                                    </td>
                                    <td class="font-semibold uppercase">{{ $list->komentar ?? '-' }}</td>
                                    <td class="font-semibold uppercase">{{ $list->keterangan }}</td>
                                    <td class="font-semibold uppercase">{{ $list->nilai ?? '-' }}</td>
                                    <td class="font-semibold">
                                        @if (Auth::user()->role === 'dosen')
                                            <button class="btn btn-warning"
                                                onclick="document.getElementById('ubah_nilai_modal_{{ $list->id_nilai }}').showModal();">
                                                Ubah Nilai
                                            </button>

                                            <dialog id="ubah_nilai_modal_{{ $list->id_nilai }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box bg-neutral">
                                                    <h3 class="modal-title capitalize text-white">
                                                        Ubah Nilai Tugas {{ $item->judul_tugas }}
                                                    </h3>
                                                    <form action="{{ route('nilai.update', $list->id_nilai) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div>
                                                            <label for="nilai"
                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai</label>
                                                            <input type="text" id="nilai" name="nilai"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                value="{{ $list->nilai }}" required />
                                                            @error('nilai')
                                                                <p class="text-red-600 text-sm">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <div>
                                                            <label for="komentar"
                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Komentar</label>
                                                            <textarea id="komentar" name="komentar"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                rows="4">{{ $list->komentar }}</textarea>
                                                        </div>

                                                        <div class="modal-action">
                                                            <button type="submit" class="btn btn-primary">Update
                                                                Nilai</button>
                                                            <button type="button"
                                                                onclick="document.getElementById('ubah_nilai_modal_{{ $list->id_nilai }}').close()"
                                                                class="btn">Tutup</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </dialog>
                                        @else
                                            <span>Undefined</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-gray-300">Tidak ada tugas terkumpul
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-action">
                <button type="button"
                    onclick="document.getElementById('pengumpulan_modal_{{ $item->id_tugas }}').close()"
                    class="btn">Tutup</button>
            </div>
        </div>
    </dialog>
@endforeach

@foreach (['tambah_tugas'] as $item)
    @php
        $type = explode('_', $item)[0];
    @endphp
    <dialog id="{{ $item }}_modal" class="modal modal-bottom sm:modal-middle">
        <form action="{{ route('store.tugas') }}" method="POST" enctype="multipart/form-data"
            class="modal-box bg-neutral">
            @csrf
            <h3 class="modal-title capitalize text-white">
                {{ str_replace('_', ' ', $item) }}
            </h3>
            <div class="modal-body">
                <!-- Pertemuan Select Box -->
                <div>
                    <label for="pertemuan"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pertemuan</label>
                    <select id="pertemuan" name="pertemuan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @for ($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}" {{ old('pertemuan') == $i ? 'selected' : '' }}>
                                Pertemuan {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('pertemuan')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="judul_tugas"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul
                        Tugas</label>
                    <input type="text" id="judul_tugas" name="judul_tugas" value="{{ old('judul_tugas') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('judul_tugas')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mata_kuliah" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata
                        Kuliah</label>
                    <select id="mata_kuliah" name="mata_kuliah"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">--- Pilih Mata Kuliah ---</option>
                        @forelse ($matakuliah as $mk)
                            <option value="{{ $mk->id_matakuliah }}"
                                {{ old('mata_kuliah') == $mk->id_matakuliah ? 'selected' : '' }}>
                                {{ $mk->mata_kuliah }}
                            </option>
                        @empty
                            <option value="">Tidak ada mata kuliah</option>
                        @endforelse
                    </select>
                    @error('mata_kuliah')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        rows="4">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lampiran_tugas"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lampiran</label>
                    <div class="relative">
                        <input type="file" id="lampiran_tugas" name="lampiran_tugas" class="hidden"
                            onchange="updateLabelAndPreview()">
                        <label id="lampiran_label" for="lampiran_tugas"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg cursor-pointer focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center">
                            Choose File
                        </label>
                        <img id="preview"
                            class="flex-shrink-0 mt-3 border rounded-lg w-full max-h-[300px] object-cover hidden"
                            src="" alt="">
                        @error('lampiran_tugas')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" onclick="document.getElementById('{{ $item }}_modal').close()"
                    class="btn btn-error capitalize">Batal</button>
                <button type="submit" class="btn btn-success capitalize">Simpan</button>
            </div>
        </form>
    </dialog>
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('lampiran_tugas');
        const previewImage = document.getElementById('preview');
        const label = document.getElementById('lampiran_label');

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
        const fileInput = document.getElementById('lampiran_tugas');
        const file = fileInput.files[0];
        const previewImg = document.getElementById('preview');
        const label = document.getElementById('lampiran_label');

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
