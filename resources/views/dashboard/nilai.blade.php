<x-dashboard.main title="Nilai">
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-6">
        @foreach (['peroleh_nilai_tertinggi', 'mata_kuliah', 'rata_rata_tertinggi'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                {{ $type == 'peroleh_nilai_tertinggi' ? 'bg-blue-300' : '' }}
                {{ $type == 'mata_kuliah' ? 'bg-rose-300' : '' }}
                {{ $type == 'rata_rata_tertinggi' ? 'bg-green-300' : '' }}
                p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'peroleh_nilai_tertinggi' ? $peroleh_nilai_tertinggi->mahasiswa->name ?? '-' : '' }}
                        {{ $type == 'mata_kuliah' ? $peroleh_nilai_tertinggi->matakuliah->mata_kuliah ?? '-' : '' }}
                        {{ $type == 'rata_rata_tertinggi' ? $rata_rata_tertinggi ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @if (Auth::user()->role === 'dosen')
            @foreach (['tambah_nilai'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 bg-white cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1 class="flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60">
                            {{ $item == 'tambah_nilai' ? 'Fitur Dosen Rekap Nilai memungkinkan dosen untuk melihat dan mengelola nilai-nilai yang telah diberikan kepada mahasiswa, serta melakukan evaluasi terhadap hasil kerja mereka.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus class="{{ $item == 'tambah_nilai' ? '' : 'hidden' }} size-5 sm:size-7 opacity-60" />
                </div>
            @endforeach
        @endif
    </div>
    <div class="flex gap-5">
        @foreach (['Daftar_nilai'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan tambahkan nilai baru.
                    </p>
                </div>
                <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    @foreach (['No', 'Nim', 'Nama', 'Mata Kuliah', 'Kuis', 'Tugas', 'UTS', 'Uas', 'Nilai Akhir', 'Grade'] as $item)
                                        <th class="uppercase font-bold">{{ $item }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekap_nilai as $i => $item)
                                    <tr>
                                        <th class="font-semibold">{{ $i + 1 }}</th>
                                        <td class="font-semibold uppercase">{{ $item->mahasiswa->nim }}</td>
                                        <td class="font-semibold uppercase">{{ $item->mahasiswa->name }}</td>
                                        <td class="font-semibold uppercase">{{ $item->matakuliah->mata_kuliah }}
                                        </td>
                                        <td class="font-semibold uppercase">{{ $item->nilai_kuis }}</td>
                                        <td class="font-semibold uppercase">{{ $item->nilai_tugas }}</td>
                                        <td class="font-semibold uppercase">{{ $item->nilai_uts }}</td>
                                        <td class="font-semibold uppercase">{{ $item->nilai_uas }}</td>
                                        <td class="font-semibold uppercase">
                                            {{ number_format($item->nilai_akhir, 2) }}</td>
                                        <td class="font-semibold uppercase">{{ $item->grade }}</td>
                                        @if (Auth::user()->role === 'dosen')
                                            <td class="flex space-x-2">
                                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                    onclick="document.getElementById('update_modal_{{ $item->id_rekap_nilai }}').showModal();" />
                                                <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                    onclick="document.getElementById('delete_modal_{{ $item->id_rekap_nilai }}').showModal();" />
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-gray text-center" colspan="11">Tidak ada Nilai</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @foreach ($rekap_nilai as $item)
        <dialog id="update_modal_{{ $item->id_rekap_nilai }}" class="modal modal-bottom sm:modal-middle">
            <form action="{{ route('update.rekap', $item->id_rekap_nilai) }}" method="POST"
                class="modal-box bg-neutral">
                @csrf
                @method('PUT')
                <h3 class="modal-title capitalize text-white">Edit Nilai</h3>
                <div class="modal-body">
                    <div>
                        <label for="nilai_kuis_{{ $item->id_rekap_nilai }}"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai Kuis
                            :</label>
                        <input type="number" name="nilai_kuis" id="nilai_kuis_{{ $item->id_rekap_nilai }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            value="{{ $item->nilai_kuis }}" required>
                    </div>
                    <div>
                        <label for="nilai_tugas_{{ $item->id_rekap_nilai }}"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai Tugas
                            :</label>
                        <input type="number" name="nilai_tugas" id="nilai_tugas_{{ $item->id_rekap_nilai }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            value="{{ $item->nilai_tugas }}" required>
                    </div>
                    <div>
                        <label for="nilai_uts_{{ $item->id_rekap_nilai }}"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai UTS :</label>
                        <input type="number" name="nilai_uts" id="nilai_uts_{{ $item->id_rekap_nilai }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            value="{{ $item->nilai_uts }}" required>
                    </div>
                    <div>
                        <label for="nilai_uas_{{ $item->id_rekap_nilai }}"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai UAS :</label>
                        <input type="number" name="nilai_uas" id="nilai_uas_{{ $item->id_rekap_nilai }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            value="{{ $item->nilai_uas }}" required>
                    </div>
                    <div class="modal-action">
                        <button type="button"
                            onclick="document.getElementById('update_modal_{{ $item->id_rekap_nilai }}').close()"
                            class="btn btn-error capitalize">Batal</button>
                        <button type="submit" class="btn btn-success capitalize">Simpan</button>
                    </div>
                </div>
            </form>
        </dialog>

        <dialog id="delete_modal_{{ $item->id_rekap_nilai }}" class="modal modal-bottom sm:modal-middle">
            <form action="{{ route('destroy.rekap', $item->id_rekap_nilai) }}" method="POST"
                class="modal-box bg-neutral">
                @csrf
                @method('DELETE')
                <h3 class="modal-title capitalize text-white">Hapus Nilai</h3>
                <div class="modal-body text-white">
                    <p>Apakah Anda yakin ingin menghapus nilai ini?</p>
                    <div class="modal-action">
                        <button type="button"
                            onclick="document.getElementById('delete_modal_{{ $item->id_rekap_nilai }}').close()"
                            class="btn btn-error capitalize">Batal</button>
                        <button type="submit" class="btn btn-danger capitalize">Hapus</button>
                    </div>
                </div>
            </form>
        </dialog>
    @endforeach


    @foreach (['tambah_nilai'] as $item)
        @php
            $type = explode('_', $item)[0];

            $fields = [
                'nilai_kuis' => [
                    'type' => 'number',
                    'label' => 'Nilai Kuis',
                    'placeholder' => 'Masukan nilai kuis...',
                ],
                'nilai_tugas' => [
                    'type' => 'number',
                    'label' => 'Nilai Tugas',
                    'placeholder' => 'Masukan nilai tugas...',
                ],
                'nilai_uts' => [
                    'type' => 'number',
                    'label' => 'Nilai UTS',
                    'placeholder' => 'Masukan nilai uts...',
                ],
                'nilai_uas' => [
                    'type' => 'number',
                    'label' => 'Nilai UAS',
                    'placeholder' => 'Masukan nilai uas...',
                ],
            ];
        @endphp
        <dialog id="{{ $item }}_modal" class="modal modal-bottom sm:modal-middle">
            <form action="{{ route('store.rekap') }}" method="POST" enctype="multipart/form-data"
                class="modal-box bg-neutral">
                @csrf
                <h3 class="modal-title capitalize text-white">
                    {{ str_replace('_', ' ', $item) }}
                </h3>
                <div class="modal-body">
                    <div>
                        <label for="mahasiswa"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mahasiswa</label>
                        <select id="mahasiswa" name="mahasiswa"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @forelse ($mahasiswa as $maha => $m)
                                <option value="{{ $m->id_mahasiswa }}"
                                    {{ old('mahasiswa') == $m ? 'selected' : '' }}>{{ $m->nim }} -
                                    {{ $m->name }}</option>
                            @empty
                                <option value="">Tidak ada mahasiswa</option>
                            @endforelse
                        </select>
                        @error('mahasiswa')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mata_kuliah"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata
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
                    @foreach ($fields as $field => $attributes)
                        <div>
                            <label for="{{ $field }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $attributes['label'] }}
                                :</label>
                            <input type="{{ $attributes['type'] }}" name="{{ $field }}"
                                id="{{ $field }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ $attributes['placeholder'] }}" required value="{{ old($field) }}">
                            @error($field)
                                <span class="validated text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('{{ $item }}_modal').close()"
                            class="btn btn-error capitalize">Batal</button>
                        <button type="submit" class="btn btn-success capitalize">Simpan</button>
                    </div>
                </div>
            </form>
        </dialog>
    @endforeach

</x-dashboard.main>
