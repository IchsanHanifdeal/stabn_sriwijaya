<x-dashboard.main title="Dosen">
    <div class="grid gap-5 md:gap-6">
        <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
            <span class="bg-blue-300 p-3 mr-4 text-gray-700 rounded-full"></span>
            <p class="text-sm font-medium capitalize text-gray-600">
                Jumlah dosen
            </p>
            <span class="ml-auto text-right">{{ $jumlah_dosen ?? '0' }}</span>
        </div>
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['dosen_terbaru', 'waktu_daftar', 'jumlah_dosen_laki-laki', 'jumlah_dosen_perempuan'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'dosen_terbaru' ? 'bg-blue-300' : '' }}
                    {{ $type == 'waktu_daftar' ? 'bg-green-300' : '' }}
                    {{ $type == 'jumlah_dosen_laki-laki' ? 'bg-amber-300' : '' }}
                    {{ $type == 'jumlah_dosen_perempuan' ? 'bg-rose-300' : '' }}
                    p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-gray-700">
                        {{ $type == 'dosen_terbaru' ? $dosen_terbaru ?? '-' : '' }}
                        {{ $type == 'waktu_daftar' ? $waktu_daftar ?? '-' : '' }}
                        {{ $type == 'jumlah_dosen_laki-laki' ? $jumlah_dosen_laki ?? '0' : '' }}
                        {{ $type == 'jumlah_dosen_perempuan' ? $jumlah_dosen_perempuan ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                    Daftar dosen
                </h1>
                <p class="text-sm opacity-60">
                    Ketahui siapa saja dosen yang sudah mendaftar.
                </p>
            </div>
            <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                @foreach (['No', 'Nip', 'Nama', 'validasi'] as $header)
                                    <th class="uppercase font-bold">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dosen as $i => $item)
                                <tr>
                                    <th>{{ $i + 1 }}</th>
                                    <td class="font-semibold">{{ $item->nip }}</td>
                                    <td class="font-semibold uppercase">{{ $item->name }}</td>
                                    <td class="font-semibold uppercase">{{ $item->user->validasi }}</td>
                                    <td class="flex items-center gap-4">
                                        @if ($item->user->validasi === 'diterima' || $item->user->validasi === 'ditolak')
                                            <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                onclick="document.getElementById('delete_modal_{{ $item->id_user }}').showModal();" />
                                            <dialog id="delete_modal_{{ $item->id_user }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box bg-neutral text-white">
                                                    <h3 class="text-lg font-bold">Hapus dosen <strong
                                                            class="text-red-500">{{ $item->name }}</strong></h3>
                                                    <div class="mt-3">
                                                        <p>Apakah Anda yakin ingin menghapus dosen dengan nama
                                                            <strong class="text-red-500">{{ $item->name }}</strong>?.
                                                        </p>
                                                    </div>
                                                    <div class="modal-action">
                                                        <button type="button"
                                                            onclick="document.getElementById('delete_modal_{{ $item->id_user }}').close()"
                                                            class="btn">Batal</button>
                                                        <form
                                                            action="{{ route('destroy.dosen', ['id_user' => $item->id_user]) }}"
                                                            method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                        @elseif ($item->user->validasi === 'menunggu validasi')
                                            <button
                                                onclick="document.getElementById('terima_modal_{{ $item->id_user }}').showModal();"
                                                class="btn btn-neutral">Terima</button> |
                                            <button
                                                onclick="document.getElementById('tolak_modal_{{ $item->id_user }}').showModal();"
                                                class="btn btn-error">Tolak</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data dosen</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($dosen as $i => $pe)
        @foreach (['terima', 'tolak'] as $action)
            <dialog id="{{ $action }}_modal_{{ $pe->id_user }}" class="modal modal-bottom sm:modal-middle">
                <form action="{{ route($action . '_' . 'dosen', ['id_user' => $pe->id_user]) }}" method="POST"
                    class="modal-box bg-neutral p-6 rounded-lg shadow-lg">
                    @csrf
                    @method('PUT')
                    <h3 class="text-xl font-bold mb-4 text-white capitalize">
                        {{ ucfirst($action) }} Validasi Pendaftaran dosen
                    </h3>
                    <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                        <p class="text-base font-medium">
                            <strong>Perhatian!</strong> Anda akan {{ $action }} validasi pendaftaran dosen
                            dengan NIP <span class="font-semibold">{{ $pe->nip }}</span> dan nama
                            <span class="font-semibold">{{ $pe->name }}</span>.
                            Apakah Anda yakin ingin melanjutkan?
                        </p>
                    </div>
                    <div class="modal-action">
                        <button
                            onclick="document.getElementById('{{ $action }}_modal_{{ $pe->id_user }}').close();"
                            class="btn btn-secondary" type="button">Tutup</button>
                        <button type="submit" class="btn btn-success capitalize">
                            {{ ucfirst($action) }}
                        </button>
                    </div>
                </form>
            </dialog>
        @endforeach
    @endforeach
</x-dashboard.main>