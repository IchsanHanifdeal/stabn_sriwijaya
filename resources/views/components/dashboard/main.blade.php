<x-main title="{{ $title }}" class="!p-0" full>
    @if (Auth::user()->validasi === 'menunggu validasi')
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
                        Menunggu Persetujuan Admin
                    </h1>
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-0">
                            @csrf
                            <button type="submit"
                                class="w-full flex flex-col items-center justify-center space-y-4 max-w-xs bg-primary hover:bg-base-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center transition duration-300 ease-in-out transform hover:scale-105 font-bold">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif(Auth::user()->validasi === 'ditolak')
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
                        Validasi anda ditolak
                    </h1>
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-0">
                            @csrf
                            <button type="submit"
                                class="w-full flex flex-col items-center justify-center space-y-4 max-w-xs bg-primary hover:bg-base-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-5 py-2.5 text-center transition duration-300 ease-in-out transform hover:scale-105 font-bold">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="drawer md:drawer-open">
            <input id="aside-dashboard" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex flex-col">
                @include('components.dashboard.navbar')
                <div class="p-4 md:p-5 bg-stone-100 w-full">
                    <div class="flex flex-col gap-5 md:gap-6 w-full min-h-screen">
                        {{ $slot }}
                    </div>
                </div>
                @include('components.footer')
            </div>
        </div>
    @endif
</x-main>
