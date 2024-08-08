<x-dashboard.main title="Pembelajaran">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['jumlah_pembelajaran', 'pembelajaran_terbaru'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                {{ $type == 'jumlah_pembelajaran' ? 'bg-blue-300' : '' }}
                {{ $type == 'pembelajaran_terbaru' ? 'bg-green-300' : '' }}
                p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'jumlah_pembelajaran' ? $jumlah_pembelajaran ?? '0' : '' }}
                        {{ $type == 'pembelajaran_terbaru' ? $pembelajaran_terbaru->nama_silabus ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @if (Auth::user()->role === 'dosen')
        <div class="flex flex-col lg:flex-row gap-5">
            @foreach (['tambah'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }} Pembelajaran
                        </h1>
                        <p class="text-sm opacity-60">
                            {{ $item == 'tambah' ? 'Fitur Tambah Pembelajaran memungkinkan pengguna untuk menambahkan topik baru dan tujuan pembelajaran, memperkaya konten pendidikan.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus class="{{ $item == 'tambah' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60" />
                </div>
            @endforeach
        </div>
    @endif
    <div class="flex gap-5">
        @foreach (['Daftar_Pembelajaran'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan tambahkan pembelajaran baru.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    @foreach (['No', 'Nama Pembelajaran', 'Deskripsi', 'pertemuan'] as $item)
                                        <th class="uppercase font-bold">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembelajaran as $i => $item)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase">{{ $item->nama_silabus }}</td>
                                        <td class="font-semibold">{{ $item->deskripsi }}</td>
                                        <td class="font-semibold uppercase">Pertemuan {{ $item->pertemuan }}</td>
                                        <td class="flex items-center gap-4">
                                            <x-lucide-scan-eye class="size-5 hover:stroke-green-500 cursor-pointer"
                                                onclick="document.getElementById('preview_modal_{{ $item->id_silabus }}').showModal();" />

                                            <dialog id="preview_modal_{{ $item->id_silabus }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box bg-neutral text-white">
                                                    <h3 class="text-lg font-bold">{{ $item->nama_silabus }}</h3>
                                                    <div
                                                        class="flex flex-col w-full mt-3 rounded-lg overflow-hidden h-full">
                                                        @if ($item->tipe_file == 'gambar')
                                                            <img src="{{ asset('storage/' . $item->file_silabus) }}"
                                                                class="border w-full h-auto"
                                                                alt="{{ $item->nama_silabus }}">
                                                        @elseif ($item->tipe_file == 'video')
                                                            <video controls class="border w-full h-auto">
                                                                <source
                                                                    src="{{ asset('storage/' . $item->file_silabus) }}"
                                                                    type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @elseif ($item->tipe_file == 'dokumen')
                                                            <embed src="{{ asset('storage/' . $item->file_silabus) }}"
                                                                type="application/pdf" class="border w-full h-full" />
                                                        @else
                                                            <p>File type is not supported for preview.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-action">
                                                        <button type="button"
                                                            onclick="document.getElementById('preview_modal_{{ $item->id_silabus }}').close()"
                                                            class="btn">Tutup</button>
                                                    </div>
                                                </div>
                                            </dialog>

                                            @if (Auth::user()->role === 'dosen')
                                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                    onclick="document.getElementById('update_modal_{{ $item->id_silabus }}').showModal();" />
                                                <dialog id="update_modal_{{ $item->id_silabus }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <form
                                                            action="{{ route('update.pembelajaran', $item->id_silabus) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <h3 class="text-lg font-bold">Update
                                                                {{ $item->nama_silabus }}
                                                            </h3>
                                                            <div
                                                                class="flex flex-col w-full mt-3 rounded-lg overflow-hidden h-full">
                                                                <!-- Pertemuan Select Box -->
                                                                <div>
                                                                    <label for="pertemuan"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pertemuan</label>
                                                                    <select id="pertemuan" name="pertemuan"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                        @for ($i = 1; $i <= 16; $i++)
                                                                            <option value="{{ $i }}"
                                                                                {{ $item->pertemuan == $i ? 'selected' : '' }}>
                                                                                Pertemuan {{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>

                                                                <!-- File Silabus -->
                                                                <div>
                                                                    <label for="file_silabus"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File
                                                                        Pembelajaran</label>
                                                                    <div class="relative">
                                                                        <input type="file"
                                                                            id="up_file_silabus_{{ $item->id_silabus }}"
                                                                            name="up_file_silabus" class="hidden"
                                                                            onchange="previewFile({{ $item->id_silabus }})">
                                                                        <label
                                                                            id="up_file_silabus_label_{{ $item->id_silabus }}"
                                                                            for="up_file_silabus_{{ $item->id_silabus }}"
                                                                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg cursor-pointer focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center">
                                                                            Choose File
                                                                        </label>
                                                                        <div id="preview_container_{{ $item->id_silabus }}"
                                                                            class="mt-3 border rounded-lg w-full max-h-[300px] object-cover">
                                                                            @if ($item->tipe_file === 'gambar')
                                                                                <img id="update_preview_{{ $item->id_silabus }}"
                                                                                    class="w-full object-cover"
                                                                                    src="{{ asset('storage/' . $item->file_silabus) }}"
                                                                                    alt="{{ $item->nama_silabus }}">
                                                                            @elseif ($item->tipe_file === 'dokumen')
                                                                                <iframe
                                                                                    id="update_preview_doc_{{ $item->id_silabus }}"
                                                                                    class="w-full h-[300px]"
                                                                                    src="{{ asset('storage/' . $item->file_silabus) }}"></iframe>
                                                                            @elseif ($item->tipe_file === 'video')
                                                                                <video
                                                                                    id="update_preview_video_{{ $item->id_silabus }}"
                                                                                    class="w-full max-h-[300px]"
                                                                                    controls>
                                                                                    <source
                                                                                        src="{{ asset('storage/' . $item->file_silabus) }}"
                                                                                        type="video/mp4">
                                                                                    Your browser does not support the
                                                                                    video
                                                                                    tag.
                                                                                </video>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Nama Silabus -->
                                                                <div>
                                                                    <label for="nama_silabus"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                                                        Silabus</label>
                                                                    <input type="text" id="nama_silabus"
                                                                        name="nama_silabus"
                                                                        value="{{ $item->nama_silabus }}"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                                                </div>

                                                                <!-- Deskripsi -->
                                                                <div>
                                                                    <label for="deskripsi"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                                                                    <textarea id="deskripsi" name="deskripsi"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                        rows="4">{{ $item->deskripsi }}</textarea>
                                                                </div>

                                                                <!-- Tipe File Select Box -->
                                                                <div>
                                                                    <label for="tipe_file"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe
                                                                        File</label>
                                                                    <select id="tipe_file" name="tipe_file"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                        <option value="gambar"
                                                                            {{ $item->tipe_file == 'gambar' ? 'selected' : '' }}>
                                                                            Gambar</option>
                                                                        <option value="dokumen"
                                                                            {{ $item->tipe_file == 'dokumen' ? 'selected' : '' }}>
                                                                            Dokumen</option>
                                                                        <option value="video"
                                                                            {{ $item->tipe_file == 'video' ? 'selected' : '' }}>
                                                                            Video</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-action">
                                                                <button type="button"
                                                                    onclick="document.getElementById('update_modal_{{ $item->id_silabus }}').close()"
                                                                    class="btn">Tutup</button>
                                                                <button type="submit"
                                                                    class="btn btn-success capitalize">Update
                                                                    Pembelajaran</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </dialog>


                                                <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                    onclick="document.getElementById('delete_modal_{{ $item->id_silabus }}').showModal();" />
                                                <dialog id="delete_modal_{{ $item->id_silabus }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <h3 class="text-lg font-bold">Hapus {{ $item->nama_silabus }}
                                                        </h3>
                                                        <div class="mt-3">
                                                            <p>Apakah Anda yakin ingin menghapus pembelajaran dengan
                                                                nama
                                                                <strong>{{ $item->nama_silabus }}</strong>?
                                                            </p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button"
                                                                onclick="document.getElementById('delete_modal_{{ $item->id_silabus }}').close()"
                                                                class="btn">Batal</button>
                                                            <form
                                                                action="{{ route('delete.pembelajaran', $item->id_silabus) }}"
                                                                method="POST" class="inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada pembelajaran</td>
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

@foreach (['tambah'] as $item)
    @php
        $type = explode('_', $item)[0];
    @endphp
    <dialog id="{{ $item }}_modal" class="modal modal-bottom sm:modal-middle">
        <form action="{{ route('store.pembelajaran') }}" method="POST" enctype="multipart/form-data"
            class="modal-box bg-neutral">
            @csrf
            <h3 class="modal-title capitalize text-white">
                {{ str_replace('_', ' ', $item) }} Pembelajaran
            </h3>
            <div class="modal-body">
                <!-- Pertemuan Select Box -->
                <div>
                    <label for="pertemuan"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pertemuan</label>
                    <select id="pertemuan" name="pertemuan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @for ($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}">Pertemuan {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- File Silabus -->
                <div>
                    <label for="file_silabus"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File Pembelajaran</label>
                    <div class="relative">
                        <input type="file" id="file_silabus" name="file_silabus" class="hidden"
                            onchange="updateLabelAndPreview()">
                        <label id="file_silabus_label" for="file_silabus"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg cursor-pointer focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center">
                            Choose File
                        </label>
                        <img id="preview"
                            class="flex-shrink-0 mt-3 border rounded-lg w-full max-h-[300px] object-cover"
                            src="" alt="">
                    </div>
                </div>

                <!-- Nama Silabus -->
                <div>
                    <label for="nama_silabus"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Silabus</label>
                    <input type="text" id="nama_silabus" name="nama_silabus"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        rows="4"></textarea>
                </div>

                <!-- Tipe File Select Box -->
                <div>
                    <label for="tipe_file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe
                        File</label>
                    <select id="tipe_file" name="tipe_file"
                        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="gambar">Gambar</option>
                        <option value="dokumen">Dokumen</option>
                        <option value="video">Video</option>
                    </select>
                </div>
            </div>
            <div class="modal-action">
                <button onclick="{{ $item }}_modal.close()" class="btn" type="button">Tutup</button>
                <button type="submit" class="btn btn-success capitalize">
                    Tambah Pembelajaran
                </button>
            </div>
        </form>
    </dialog>
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('file_silabus');
        const previewImage = document.getElementById('preview');
        const label = document.getElementById('file_silabus_label');

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
        const fileInput = document.getElementById('file_silabus');
        const previewImage = document.getElementById('preview');
        const label = document.getElementById('file_silabus_label');
        const file = fileInput.files[0];

        if (file) {
            label.textContent = file.name;

            if (file.type.startsWith('image/')) {
                const blob = URL.createObjectURL(file);
                previewImage.style.display = 'block';
                previewImage.src = blob;
            } else {
                previewImage.style.display = 'none';
                previewImage.src = '';
            }
        } else {
            label.textContent = 'Choose File';
            previewImage.style.display = 'none';
            previewImage.src = '';
        }
    }
</script>

<script>
    function previewFile(itemId) {
        const fileInput = document.getElementById(`up_file_silabus_${itemId}`);
        const previewImg = document.getElementById(`update_preview_${itemId}`);
        const previewDoc = document.getElementById(`update_preview_doc_${itemId}`);
        const previewVideo = document.getElementById(`update_preview_video_${itemId}`);
        const fileLabel = document.getElementById(`up_file_silabus_label_${itemId}`);

        const file = fileInput.files[0];

        if (file) {
            const fileType = file.type;
            const blobUrl = URL.createObjectURL(file);

            // Hide all previews
            if (previewImg) previewImg.classList.add('hidden');
            if (previewDoc) previewDoc.classList.add('hidden');
            if (previewVideo) previewVideo.classList.add('hidden');

            // Show the appropriate preview
            if (fileType.startsWith('image/')) {
                if (previewImg) {
                    previewImg.src = blobUrl;
                    previewImg.classList.remove('hidden');
                }
            } else if (fileType.startsWith('application/') || fileType === 'text/plain') {
                if (previewDoc) {
                    previewDoc.src = blobUrl;
                    previewDoc.classList.remove('hidden');
                }
            } else if (fileType.startsWith('video/')) {
                if (previewVideo) {
                    previewVideo.src = blobUrl;
                    previewVideo.classList.remove('hidden');
                }
            }

            // Update the label with the file name
            fileLabel.textContent = file.name;
        } else {
            // Reset if no file is selected
            fileLabel.textContent = 'Choose File';
            if (previewImg) previewImg.classList.add('hidden');
            if (previewDoc) previewDoc.classList.add('hidden');
            if (previewVideo) previewVideo.classList.add('hidden');
        }
    }
</script>
