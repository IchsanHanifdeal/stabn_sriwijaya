<x-dashboard.main title="Setting">
    <div class="flex gap-5">
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                    Daftar Pengaturan
                </h1>
                <p class="text-sm opacity-60">
                    Kelola Mata kuliah, Jurusan.
                </p>
            </div>
            <div class="flex flex-col bg-zinc-50 rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                <div class="overflow-x-auto">
                    <table class="table table-zebra text-center">
                        <thead>
                            <tr>
                                @foreach (['No', 'Nama Pengaturan'] as $item)
                                    <th class="uppercase font-bold">{{ $item }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main>
