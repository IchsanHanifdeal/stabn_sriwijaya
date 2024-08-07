<x-main title="Pilih Daftar" style="background-image: url('{{ asset('images/auth_bg.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
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
                    Pilihan daftar
                </h1>
                <button onclick="window.location.href='{{ route('register_dosen') }}'" class="w-full text-dark font-bold bg-neutral-300 hover:bg-warning focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center">Dosen</button>
                <button onclick="window.location.href='{{ route('register_mahasiswa') }}'"
                    class="w-full text-dark font-bold bg-neutral-300 hover:bg-warning focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center">Mahasiswa</button>
                <button onclick="window.location.href='{{ route('login') }}'" class="w-full text-dark font-bold bg-secondary hover:bg-accent focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center">Kembali</button>
            </div>
        </div>
    </div>
</x-main>
