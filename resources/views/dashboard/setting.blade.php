<x-dashboard.main title="Setting">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['jumlah_jurusan', 'jumlah_mata_kuliah'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                {{ $type == 'jumlah_jurusan' ? 'bg-blue-300' : '' }}
                {{ $type == 'jumlah_mata_kuliah' ? 'bg-green-300' : '' }}
                p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'jumlah_jurusan' ? $jumlah_jurusan ?? '0' : '' }}
                        {{ $type == 'jumlah_mata_kuliah' ? $jumlah_mata_kuliah ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['tambah_jurusan', 'tambah_mata_kuliah'] as $item)
            <div onclick="document.getElementById('{{ $item }}_modal').showModal()"
                class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        @if ($item == 'tambah_jurusan')
                            Menambahkan data jurusan.
                        @elseif ($item == 'tambah_mata_kuliah')
                            Menambahkan data mata kuliah.
                        @endif
                    </p>
                </div>
                <x-lucide-plus class="size-5 sm:size-7 opacity-60" />
            </div>
        @endforeach
    </div>
    <div class="grid sm:grid-cols-1 xl:grid-cols-2 flex gap-5">
        @foreach (['jurusan', 'mata_kuliah'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan tambahkan {{ str_replace('_', ' ', $item) }} baru.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    @foreach (['No', 'Nama ' . str_replace('_', ' ', $item)] as $header)
                                        <th class="uppercase font-bold">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($item == 'jurusan')
                                    @foreach ($jurusan as $i => $dataItem)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="font-semibold uppercase">{{ $dataItem->kode_jurusan }} -
                                                {{ $dataItem->nama_jurusan }}</td>
                                            <td class="flex items-center gap-4">
                                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                    onclick="document.getElementById('edit_jurusan_modal_{{ $dataItem->id_jurusan }}').showModal();" />
                                                <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                    onclick="document.getElementById('delete_jurusan_modal_{{ $dataItem->id_jurusan }}').showModal();" />
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($mataKuliah as $i => $dataItem)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="font-semibold uppercase">{{ $dataItem->mata_kuliah }}</td>
                                            <td class="flex items-center gap-4">
                                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                    onclick="document.getElementById('edit_mata_kuliah_modal_{{ $dataItem->id_matakuliah }}').showModal();" />
                                                <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                    onclick="document.getElementById('delete_mata_kuliah_modal_{{ $dataItem->id_matakuliah }}').showModal();" />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>

@foreach (['jurusan', 'mata_kuliah'] as $item)
    @php
        $type = $item;
        $data = $type == 'jurusan' ? $jurusan : $mataKuliah;
    @endphp

    @foreach ($data as $dataItem)
        <!-- Edit Modal -->
        <dialog id="edit_{{ $type }}_modal_{{ $dataItem->id_jurusan ?? $dataItem->id_matakuliah }}" class="modal modal-bottom sm:modal-middle">
            <form action="{{ route($type . '.update', ['id' => $dataItem->id_jurusan ?? $dataItem->id_matakuliah]) }}" method="POST" class="modal-box bg-neutral">
                @csrf
                @method('PUT')
                <h3 class="modal-title capitalize text-white">
                    Edit {{ str_replace('_', ' ', $type) }}
                </h3>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-white">Nama {{ str_replace('_', ' ', $type) }}</span>
                    </label>
                    <input type="text" name="name" value="{{ $dataItem->mata_kuliah ?? $dataItem->nama_jurusan }}" placeholder="Nama {{ str_replace('_', ' ', $type) }}"
                        class="input input-bordered w-full" required>
                </div>

                @if ($type == 'jurusan')
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text text-white">Kode Jurusan</span>
                        </label>
                        <input type="text" name="kode_jurusan" value="{{ $dataItem->kode_jurusan }}" placeholder="Kode Jurusan"
                            class="input input-bordered w-full" required>
                    </div>
                @endif

                <div class="modal-action">
                    <button type="button" onclick="document.getElementById('edit_{{ $type }}_modal_{{ $dataItem->id_jurusan ?? $dataItem->id_matakuliah }}').close();"
                            class="btn">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </dialog>
    @endforeach
@endforeach

@foreach (['jurusan', 'mata_kuliah'] as $item)
    @php
        $type = $item;
        $data = $type == 'jurusan' ? $jurusan : $mataKuliah;
    @endphp

    @foreach ($data as $dataItem)
        <!-- Delete Modal -->
        <dialog id="delete_{{ $type }}_modal_{{ $dataItem->id_jurusan ?? $dataItem->id_matakuliah }}" class="modal modal-bottom sm:modal-middle">
            <form action="{{ route($type . '.destroy', ['id' => $dataItem->id_jurusan ?? $dataItem->id_matakuliah]) }}" method="POST" class="modal-box bg-neutral">
                @csrf
                @method('DELETE')
                <h3 class="modal-title capitalize text-white">
                    Hapus {{ str_replace('_', ' ', $type) }}
                </h3>
                <p class="text-white">Apakah Anda yakin ingin menghapus {{ str_replace('_', ' ', $type) }} ini?</p>
                <div class="modal-action">
                    <button type="button" onclick="document.getElementById('delete_{{ $type }}_modal_{{ $dataItem->id_jurusan ?? $dataItem->id_matakuliah }}').close();"
                            class="btn">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </dialog>
    @endforeach
@endforeach

@foreach (['tambah_jurusan', 'tambah_mata_kuliah'] as $item)
    @php
        $type = explode('_', $item)[1];
        $route = $type == 'jurusan' ? route('jurusan.store') : route('mata_kuliah.store');
    @endphp
    <dialog id="{{ $item }}_modal" class="modal modal-bottom sm:modal-middle">
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="modal-box bg-neutral">
            @csrf
            <h3 class="modal-title capitalize text-white">
                {{ str_replace('_', ' ', $item) }}
            </h3>
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-white">Nama {{ str_replace('_', ' ', $type) }}</span>
                </label>
                <input type="text" name="name" placeholder="Nama {{ str_replace('_', ' ', $type) }}"
                    class="input input-bordered w-full" required>
            </div>
            @if ($type == 'jurusan')
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text text-white">Kode Jurusan</span>
                    </label>
                    <input type="text" name="kode_jurusan" placeholder="Kode Jurusan"
                        class="input input-bordered w-full" required>
                </div>
            @endif
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('{{ $item }}_modal').close();"
                    class="btn">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>
@endforeach
