@props(['id', 'title', 'action', 'method' => 'POST', 'data' => null])

<dialog id="{{ $id }}" class="rounded-lg p-0 w-96">
    <form method="POST" action="{{ $action }}">
        @csrf
        @method($method)
        <div class="p-6">
            <h3 class="text-xl font-semibold">{{ $title }}</h3>
            <div class="mt-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" value="{{ $data ? $data->name : '' }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            @if (isset($data->kode_jurusan))
                <div class="mt-4">
                    <label for="kode_jurusan" class="block text-sm font-medium text-gray-700">Kode Jurusan</label>
                    <input type="text" name="kode_jurusan" id="kode_jurusan" value="{{ $data->kode_jurusan }}"
                           required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            @endif
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="document.getElementById('{{ $id }}').close();"
                        class="px-4 py-2 bg-gray-300 rounded-md">Batal</button>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md">Simpan</button>
            </div>
        </div>
    </form>
</dialog>
