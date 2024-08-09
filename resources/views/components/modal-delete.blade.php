@props(['id', 'action'])

<dialog id="{{ $id }}" class="rounded-lg p-0 w-96">
    <form method="POST" action="{{ $action }}">
        @csrf
        @method('DELETE')
        <div class="p-6">
            <h3 class="text-xl font-semibold">Konfirmasi Hapus</h3>
            <p class="mt-4">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="document.getElementById('{{ $id }}').close();"
                        class="px-4 py-2 bg-gray-300 rounded-md">Batal</button>
                <button type="submit" class="ml-2 px-4 py-2 bg-red-500 text-white rounded-md">Hapus</button>
            </div>
        </div>
    </form>
</dialog>
