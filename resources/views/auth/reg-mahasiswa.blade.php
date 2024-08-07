@php
    $fields = [
        'nim' => [
            'type' => 'number',
            'label' => 'NIM',
            'placeholder' => 'Masukan nim...',
        ],
        'nama' => [
            'type' => 'text',
            'label' => 'Nama',
            'placeholder' => 'Masukan nama...',
        ],
        'username' => [
            'type' => 'text',
            'label' => 'Username',
            'placeholder' => 'Masukan username...',
        ],
        'email' => [
            'type' => 'email',
            'label' => 'Email',
            'placeholder' => 'Masukan email...',
        ],
    ];
@endphp

<x-main title="Registrasi Mahasiswa">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold">
            <img class="w-8 h-8 mr-2"
                src="https://pasca.stabn-sriwijaya.ac.id/wp-content/uploads/2022/07/logo-stabn-sriwijaya.png"
                alt="logo"> STABN Sriwijaya
        </a>
        <div class="w-full bg-neutral rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1
                    class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white text-center uppercase">
                    Registrasi Mahasiswa
                </h1>
                <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('store.mahasiswa') }}">
                    @csrf
                    @foreach ($fields as $field => $attributes)
                        <div>
                            <label for="{{ $field }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $attributes['label'] }}
                                :</label>
                            <input type="{{ $attributes['type'] }}" name="{{ $field }}" id="{{ $field }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ $attributes['placeholder'] }}" value="{{ old($field) }}" required>
                            @error($field)
                                <span class="validated text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div>
                        <label for="major"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jurusan :</label>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            id="major" name="jurusan" required>
                            <option value="">--- Pilih Jurusan ---</option>
                            @foreach ($jurusan as $item)
                                <option value="{{ $item->id_jurusan }}"
                                    @if (old('jurusan') == $item->id_jurusan) selected @endif>{{ $item->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('jurusan')
                            <span class="validated text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password :</label>
                        <input type="password" name="password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Masukan password..." required>
                        @error('password')
                            <span class="validated text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full text-dark font-bold bg-neutral-300 hover:bg-warning focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center">Daftar</button>
                    <button type="button" onclick="window.location.href='{{ route('pilihan_daftar') }}'"
                        class="w-full text-dark font-bold bg-secondary hover:bg-accent focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center">Kembali</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Sudah punya akun? <a href="{{ route('login') }}"
                            class="font-medium text-primary-600 hover:underline dark:text-primary-500">Masuk
                            Sekarang</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-main>