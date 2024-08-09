<div class="navbar bg-base-100">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li>
                    <a href="{{ route('dashboard') }}" class="{!! Request::path() == 'dashboard' ? 'active' : '' !!}">
                        <x-lucide-gauge class="h-5 w-5 mr-2" />
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembelajaran') }}" class="{!! preg_match('#^dashboard/pembelajaran.*#', Request::path()) ? 'active' : '' !!}">
                        <x-lucide-book-open class="h-5 w-5 mr-2" />
                        Pembelajaran
                    </a>
                </li>
                <li>
                    <a href="{{ route('materi') }}" class="{!! preg_match('#^dashboard/materi.*#', Request::path()) ? 'active' : '' !!}">
                        <x-lucide-file-text class="h-5 w-5 mr-2" />
                        Materi
                    </a>
                </li>
                @if (Auth::user()->role === 'dosen')
                    <li>
                        <a href="{{ route('mahasiswa') }}" class="{!! preg_match('#^dashboard/mahasiswa.*#', Request::path()) ? 'active' : '' !!}">
                            <x-lucide-users class="h-5 w-5 mr-2" />
                            Mahasiswa
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('absensi') }}" class="{!! preg_match('#^dashboard/absensi.*#', Request::path()) ? 'active' : '' !!}">
                        <x-lucide-calendar class="h-5 w-5 mr-2" />
                        Absensi
                    </a>
                </li>
                <li>
                    <a href="{{ route('tugas') }}" class="{!! preg_match('#^dashboard/tugas.*#', Request::path()) ? 'active' : '' !!}">
                        <x-lucide-clipboard class="h-5 w-5 mr-2" />
                        Tugas
                    </a>
                </li>
                <li>
                    <a href="{{ route('nilai') }}" class="{!! preg_match('#^dashboard/nilai.*#', Request::path()) ? 'active' : '' !!}">
                        <x-lucide-award class="h-5 w-5 mr-2" />
                        Nilai
                    </a>
                </li>
            </ul>
        </div>
        <a class="btn btn-ghost text-xl uppercase" href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li>
                <a class="{!! Request::path() == 'dashboard' ? 'active' : '' !!}" href="{{ route('dashboard') }}">
                    <x-lucide-gauge class="h-5 w-5 mr-2" />
                    Dashboard
                </a>
            </li>
            <li>
                <a class="{!! preg_match('#^dashboard/pembelajaran.*#', Request::path()) ? 'active' : '' !!}" href="{{ route('pembelajaran') }}">
                    <x-lucide-book-open class="h-5 w-5 mr-2" />
                    Pembelajaran
                </a>
            </li>
            <li>
                <a class="{!! preg_match('#^dashboard/materi.*#', Request::path()) ? 'active' : '' !!}" href="{{ route('materi') }}">
                    <x-lucide-file-text class="h-5 w-5 mr-2" />
                    Materi
                </a>
            </li>
            @if (Auth::user()->role === 'dosen')
                <li>
                    <a class="{!! preg_match('#^dashboard/mahasiswa.*#', Request::path()) ? 'active' : '' !!}" href="{{ route('mahasiswa') }}">
                        <x-lucide-users class="h-5 w-5 mr-2" />
                        Mahasiswa
                    </a>
                </li>
            @endif
            <li>
                <a class="{!! preg_match('#^dashboard/absensi.*#', Request::path()) ? 'active' : '' !!}" href="{{ route('absensi') }}">
                    <x-lucide-calendar class="h-5 w-5 mr-2" />
                    Absensi
                </a>
            </li>
            <li>
                <a class="{!! preg_match('#^dashboard/tugas.*#', Request::path()) ? 'active' : '' !!}" href="{{ route('tugas') }}">
                    <x-lucide-clipboard class="h-5 w-5 mr-2" />
                    Tugas
                </a>
            </li>
            <li>
                <a class="{!! preg_match('#^dashboard/nilai.*#', Request::path()) ? 'active' : '' !!}" href="{{ route('nilai') }}">
                    <x-lucide-award class="h-5 w-5 mr-2" />
                    Nilai
                </a>
            </li>
        </ul>
    </div>
    <div class="navbar-end flex items-center space-x-4">
        <!-- Dropdown User -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" class="flex items-center btn btn-ghost">
                <img class="w-8 h-8 rounded-full border border-gray-400"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="User Avatar">
                <span class="ml-2">{{ Auth::user()->name }}</span>
            </div>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 mt-2">
                <li>
                    <a href="{{ route('profile') }}" class="flex items-center {!! preg_match('#^dashboard/profile.*#', Request::path()) ? 'active' : '' !!}">
                        <x-lucide-user class="h-5 w-5 mr-2" />
                        Profile
                    </a>
                </li>
                @if (Auth::user()->role === 'dosen')
                    <li>
                        <a href="{{ route('setting') }}" class="{!! preg_match('#^dashboard/setting.*#', Request::path()) ? 'active' : '' !!} flex items-center">
                            <x-lucide-settings class="h-5 w-5 mr-2" />
                            Settings
                        </a>
                    </li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                        @csrf
                        <x-lucide-log-out class="h-5 w-5 mr-2" />
                        <button type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
