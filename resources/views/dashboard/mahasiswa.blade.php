<x-dashboard.main title="Mahasiswa">
    <div class="grid gap-5 md:gap-6">
        <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
            <span class="bg-blue-300 p-3 mr-4 text-gray-700 rounded-full"></span>
            <p class="text-sm font-medium capitalize text-gray-600">
                Jumlah Mahasiswa
            </p>
            <span class="ml-auto text-right">{{ $jumlah_mahasiswa ?? '0' }}</span>
        </div>
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['mahasiswa_terbaru', 'waktu_daftar', 'jumlah_mahasiswa_laki-laki', 'jumlah_mahasiswa_perempuan'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'mahasiswa_terbaru' ? 'bg-blue-300' : '' }}
                    {{ $type == 'waktu_daftar' ? 'bg-green-300' : '' }}
                    {{ $type == 'jumlah_mahasiswa_laki-laki' ? 'bg-amber-300' : '' }}
                    {{ $type == 'jumlah_mahasiswa_perempuan' ? 'bg-rose-300' : '' }}
                    p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'mahasiswa_terbaru' ? $mahasiswa_terbaru ?? '-' : '' }}
                        {{ $type == 'waktu_daftar' ? $waktu_daftar ?? '-' : '' }}
                        {{ $type == 'jumlah_mahasiswa_laki-laki' ? $jumlah_mahasiswa_laki ?? '0' : '' }}
                        {{ $type == 'jumlah_mahasiswa_perempuan' ? $jumlah_mahasiswa_perempuan ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                    Daftar Mahasiswa
                </h1>
                <p class="text-sm opacity-60">
                    Ketahui siapa saja mahasiswa yang sudah mendaftar.
                </p>
            </div>
            <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                @foreach (['No', 'Nim', 'Nama', 'Jurusan', 'Jenis Kelamin', 'Tempat/Tanggal Lahir', 'Alamat', 'No HP'] as $header)
                                    <th class="uppercase font-bold">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswa as $i => $item)
                                <tr>
                                    <th>{{ $i + 1 }}</th>
                                    <td class="font-semibold">{{ $item->nim }}</td>
                                    <td class="font-semibold uppercase">{{ $item->name }}</td>
                                    <td class="font-semibold uppercase">{{ $item->jurusan->kode_jurusan }} -
                                        {{ $item->jurusan->nama_jurusan }}</td>
                                    <td class="font-semibold uppercase">
                                        @if ($item->jenis_kelamin === 'L')
                                            Laki-laki
                                        @elseif($item->jenis_kelamin === 'P')
                                            Perempuan
                                        @else
                                            Undefined
                                        @endif
                                    </td>
                                    <td class="font-semibold uppercase">
                                        {{ $item->tempat_lahir }}/{{ $item->tanggal_lahir }}</td>
                                    <td class="font-semibold uppercase">{{ $item->alamat }}</td>
                                    <td class="font-semibold uppercase">{{ $item->no_hp }}</td>
                                    <td>
                                        <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                            onclick="document.getElementById('delete_modal_{{ $item->id_user }}').showModal();" />
                                        <dialog id="delete_modal_{{ $item->id_user }}"
                                            class="modal modal-bottom sm:modal-middle">
                                            <div class="modal-box bg-neutral text-white">
                                                <h3 class="text-lg font-bold">Hapus mahasiswa <strong
                                                        class="text-red-500">{{ $item->name }}</strong></h3>
                                                <div class="mt-3">
                                                    <p>Apakah Anda yakin ingin menghapus mahasiswa dengan nama
                                                        <strong class="text-red-500">{{ $item->name }}</strong>?.
                                                    </p>
                                                </div>
                                                <div class="modal-action">
                                                    <button type="button"
                                                        onclick="document.getElementById('delete_modal_{{ $item->id_user }}').close()"
                                                        class="btn">Batal</button>
                                                    <form action="{{ route('destroy.mahasiswa', ['id_user' => $item->id_user]) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data mahasiswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main>
