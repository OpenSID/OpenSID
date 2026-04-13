{{-- resources/views/partials/hero.blade.php --}}
@php
    $bg_header = $latar_website;
@endphp
<div class="relative h-[275px] sm:h-[300px] md:h-[350px] lg:h-[450px] bg-white text-gray-900 overflow-hidden">

    {{-- Navigation Menu at the top --}}
    <header class="absolute top-0 left-0 right-0 z-30">
        <div class="relative z-10">
            {{-- Desktop Header --}}
            <div class="hidden lg:flex items-center justify-between mt-4">

                {{-- LEFT: Logo + Menu --}}
                <div class="flex items-center">

                    {{-- Logo --}}
                    <div class="flex items-center pr-4">
                        <a href="{{ ci_route() }}" class="block">
                            <img src="{{ gambar_desa($desa['logo']) }}"
                                alt="Logo {{ ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']) }}"
                                class="h-12">
                        </a>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ ucfirst(setting('sebutan_desa')) }}
                            </p>
                            <p class="text-sm font-semibold -mt-1 text-gray-900">
                                {{ ucwords($desa['nama_desa']) }}
                            </p>
                        </div>
                    </div>

                    {{-- Navigation --}}
                    <nav class="text-sm text-gray-900">
                        <ul class="flex items-center">
                            @if (menu_tema())
                                @foreach (menu_tema() as $menu)
                                    @php $has_dropdown = count($menu['childrens'] ?? []) > 0; @endphp
                                    <li class="relative" @if($has_dropdown) x-data="{dropdown:false}" @endif>
                                        <a href="{{ $has_dropdown ? '#!' : $menu['link_url'] }}"
                                            class="px-2 py-2 font-medium hover:text-green-600 transition" @if($has_dropdown)
                                                @mouseover="dropdown=true" @mouseleave="dropdown=false"
                                            @click.prevent="dropdown=!dropdown" @endif>
                                            {{ $menu['nama'] }}
                                            @if($has_dropdown)
                                                <i class="fas fa-chevron-down text-xs ml-1" :class="{'rotate-180':dropdown}"></i>
                                            @endif
                                        </a>

                                        @if($has_dropdown)
                                            <ul class="absolute top-full left-0 bg-white shadow-lg rounded-md border mt-1 min-w-max"
                                                x-show="dropdown" x-transition @mouseover="dropdown=true"
                                                @mouseleave="dropdown=false">
                                                @foreach ($menu['childrens'] as $child)
                                                    <li>
                                                        <a href="{{ $child['link_url'] }}"
                                                            class="block px-5 py-3 hover:bg-green-50 hover:text-green-600">
                                                            {{ $child['nama'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </nav>
                </div>

                {{-- RIGHT: Login Button with Popup --}}
                <div class="p-0" x-data="{ loginPopup: false }">
                    <button @click="loginPopup = true" class="inline-flex items-center px-6 py-2 text-sm font-semibold text-white
                           bg-green-600 rounded-full shadow-md
                           hover:bg-green-700 hover:shadow-lg
                           transition">
                        Login
                    </button>

                    <!-- Login Popup Modal -->
                    <div x-show="loginPopup" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="loginPopup = false"
                        @keydown.escape.window="loginPopup = false" style="display: none;">

                        <!-- Backdrop -->
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                        <!-- Modal Content -->
                        <div class="relative bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm transform"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            @click.stop>

                            <!-- Close Button -->
                            <button @click="loginPopup = false"
                                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <!-- Header -->
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900">Pilih Halaman Login</h3>
                                <p class="text-sm text-gray-500 mt-1">Silakan pilih tujuan login Anda</p>
                            </div>

                            <!-- Login Options -->
                            <div class="space-y-3">
                                <a href="/layanan-mandiri"
                                    class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-green-500 hover:bg-green-50 transition group">
                                    <div
                                        class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center group-hover:bg-green-700 transition">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 group-hover:text-green-700">Layanan
                                            Mandiri</h4>
                                        <p class="text-xs text-gray-500">Login untuk warga desa</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>

                                <a href="/siteman"
                                    class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition group">
                                    <div
                                        class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center group-hover:bg-blue-700 transition">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 group-hover:text-blue-700">Halaman Admin
                                        </h4>
                                        <p class="text-xs text-gray-500">Login untuk administrator</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile Header --}}
            <div class="lg:hidden fixed top-0 left-0 right-0 z-40">
                <div class="bg-green-700 lg:bg-white shadow-md flex items-center justify-between px-4 py-2">
                    <div class="flex items-center gap-2">
                        <img src="{{ gambar_desa($desa['logo']) }}" class="h-10">
                        <div>
                            <p class="text-white lg:text-black text-sm font-semibold">
                                {{ ucfirst(setting('sebutan_desa')) }}
                            </p>
                            <p class="text-white lg:text-black text-sm font-semibold -mt-1">
                                {{ ucwords($desa['nama_desa']) }}
                            </p>
                        </div>
                    </div>
                    @include('theme::commons.mobile_menu')
                </div>
            </div>
        </div>
    </header>


    {{-- Hero content --}}
    <div class="relative z-10 h-full flex flex-col lg:flex-row pt-16 lg:pt-16">
        {{-- Desktop content --}}
        <div class="hidden lg:flex flex-1 flex-col justify-center">
            <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold mb-3 leading-tight text-gray-900">
                Website Resmi
            </h1>
            <h2 class="text-5xl lg:text-6xl xl:text-7xl font-bold mb-5 leading-tight text-gray-900">
                {{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}
            </h2>
            <div class="text-sm lg:text-base xl:text-lg space-y-1 text-gray-600">
                <p>{{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}
                    {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}
                </p>
                <p>Provinsi {{ ucwords($desa['nama_propinsi']) }}</p>
            </div>
        </div>

        {{-- Mobile content --}}
        <div class="lg:hidden flex-1 relative">

            {{-- Background Image --}}
            <div class="torn-paper-mobile mt-16 absolute inset-0 bg-center bg-cover"
                style="background-image: url('{{ $bg_header }}');">
            </div>

            {{-- Overlay Content --}}
            <div class="relative z-10 flex flex-col items-left justify-center h-full px-4 text-center">

                <h1 class="text-2xl font-bold text-white mt-16">Website Resmi</h1>
                <h2 class="text-xl font-bold text-white">
                    {{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}
                </h2>

                <div class="text-xs text-bold text-white mt-1">
                    <p>
                        {{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}
                        {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}
                    </p>
                    <p>Provinsi {{ ucwords($desa['nama_propinsi']) }}</p>
                </div>

                {{-- Clock for Mobile --}}
                @if(theme_config('jam_kerja') == '1')
                    <div class="mt-2 bg-black/30 backdrop-blur-md rounded-lg p-2 inline-block mx-auto">
                        <div class="text-center">
                            <div id="digital-date-mobile" class="text-sm text-white font-medium mb-1">
                                Loading...
                            </div>
                            <div id="working-hours-mobile" class="text-xs text-white">
                                {{-- Working hours for current day will be inserted here by JavaScript --}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Marquee --}}
            @if ($teks_berjalan)
                <div class="absolute bottom-0 left-0 right-0 py-1 text-white text-xs z-20 bg-black/40">
                    <marquee onmouseover="this.stop();" onmouseout="this.start();">
                        @foreach ($teks_berjalan as $marquee)
                            <span class="px-3">
                                {{ $marquee['teks'] }}
                                @if (trim($marquee['tautan']) && $marquee['judul_tautan'])
                                    <a href="{{ $marquee['tautan'] }}" class="underline hover:text-green-300 ml-1">
                                        {{ $marquee['judul_tautan'] }}
                                    </a>
                                @endif
                            </span>
                        @endforeach
                    </marquee>
                </div>
            @endif

        </div>


        {{-- Torn Paper Image - Desktop --}}
        <div class="hidden mt-12 lg:flex w-100px h-100px items-center justify-center">
            {{-- Clock Overlay (outside torn-paper to avoid filter clipping) --}}
            @if(theme_config('jam_kerja') == '1')
                <div class="absolute flex items-center justify-center z-40">
                    <div class="bg-black/10 backdrop-blur-md rounded-lg px-3 py-2 shadow-lg">
                        <div class="text-center">
                            <div id="digital-clock" class="text-lg font-mono font-bold text-white">
                                00:00:00
                            </div>
                            <div id="digital-date" class="text-xs text-white">
                                Loading...
                            </div>
                            <div id="working-hours" class="text-xs text-white mt-1">
                                {{-- Working hours for current day will be inserted here by JavaScript --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- Torn Paper Image --}}
            <div class="torn-paper-image w-full h-full">
                <img src="{{ $bg_header }}" alt="Desa Image" class="w-full h-full object-cover">

                {{-- Marquee on Desktop Image --}}
                @if ($teks_berjalan)
                    <div
                        class="absolute mb-2 bottom-0 left-0 right-0 py-1 sm:py-1.5 bg-green-800 bg-opacity-20 text-white text-xs z-20">
                        <div class="marquee-container">
                            <marquee onmouseover="this.stop();" onmouseout="this.start();" class="block">
                                @foreach ($teks_berjalan as $marquee)
                                    <span class="px-2 sm:px-3">
                                        {{ $marquee['teks'] }}
                                        @if (trim($marquee['tautan']) && $marquee['judul_tautan'])
                                            <a href="{{ $marquee['tautan'] }}"
                                                class="hover:text-green-300 underline">{{ $marquee['judul_tautan'] }}</a>
                                        @endif
                                    </span>
                                @endforeach
                            </marquee>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    .bg-green {
        --tw-bg-opacity: 1;
        background-color: rgb(34 197 94 / var(--tw-bg-opacity));
    }

    .min-w-max {
        min-width: max-content;
    }

    nav a {
        transition: all 0.3s ease;
    }

    .torn-paper-image {
        filter: url(#filter_tornpaper);
        border-radius: 50%;
    }

    .torn-paper-mobile {
        filter: url(#filter_tornpaper);
        background-size: cover;
        background-position: center;
    }

    .torn-paper-mobile::after {
        content: "";
        position: absolute;
        inset: 0;
        background-color: #094822;
        /* green-900 */
        opacity: 0.2;
        /* 20% */
        pointer-events: none;
    }

    @media (max-width: 1024px) {
        [x-cloak] {
            display: none !important;
        }

        .mobile-menu-nav {
            z-index: 999999;
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 320px;
            max-width: 90vw;
        }

        .lg\\:hidden.fixed {
            z-index: 40 !important;
        }
    }

    @media (max-width: 768px) {

        #working-hours .bg-green-500,
        #working-hours .bg-red-500,
        #working-hours .bg-yellow-500,
        #working-hours-mobile .bg-green-500,
        #working-hours-mobile .bg-red-500,
        #working-hours-mobile .bg-yellow-500 {
            font-size: 10px;
            padding: 2px 6px;
        }
    }
</style>

<script>
    // Working hours data from PHP
    const workingHoursData = @json($jam_kerja ?? []);

    function updateClock() {
        const now = new Date();

        // Format time (24-hour format)
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;

        // Format date
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const dateString = now.toLocaleDateString('id-ID', options);

        // Get current day name in Indonesian
        const dayNames = {
            'Sunday': 'Minggu',
            'Monday': 'Senin',
            'Tuesday': 'Selasa',
            'Wednesday': 'Rabu',
            'Thursday': 'Kamis',
            'Friday': 'Jumat',
            'Saturday': 'Sabtu'
        };

        const currentDay = now.toLocaleDateString('en-US', { weekday: 'long' });
        const currentDayIndo = dayNames[currentDay];

        // Find working hours for current day
        let workingHoursHTML = '';

        if (workingHoursData && workingHoursData.length > 0) {
            const todaySchedule = workingHoursData.find(schedule =>
                schedule.nama_hari.toLowerCase() === currentDayIndo.toLowerCase()
            );

            if (todaySchedule) {
                if (todaySchedule.status) {
                    const masuk = todaySchedule.jam_masuk.substring(0, 5);
                    const keluar = todaySchedule.jam_keluar.substring(0, 5);
                    const workingHoursText = `${masuk} - ${keluar}`;

                    const current = `${hours}:${minutes}`;
                    const isOpen = current >= masuk && current <= keluar;

                    workingHoursHTML = isOpen
                        ? `<span class="bg-green-500 text-white px-2 py-0.5 rounded text-xs">Buka</span> ${workingHoursText}`
                        : `<span class="bg-yellow-500 text-white px-2 py-0.5 rounded text-xs">Tutup</span> ${workingHoursText}`;
                } else {
                    workingHoursHTML = '<span class="bg-red-500 text-white px-2 py-0.5 rounded text-xs">Libur</span>';
                }
            }
        }

        // Update elements
        const clockElement = document.getElementById('digital-clock');
        const dateElement = document.getElementById('digital-date');
        const workingHoursElement = document.getElementById('working-hours');
        const dateMobileElement = document.getElementById('digital-date-mobile');
        const workingHoursMobileElement = document.getElementById('working-hours-mobile');

        if (clockElement) clockElement.textContent = timeString;
        if (dateElement) dateElement.textContent = dateString;
        if (dateMobileElement) dateMobileElement.textContent = dateString;
        if (workingHoursElement) workingHoursElement.innerHTML = workingHoursHTML;
        if (workingHoursMobileElement) workingHoursMobileElement.innerHTML = workingHoursHTML;
    }

    // Initialize clock
    updateClock();
    setInterval(updateClock, 1000);

    document.addEventListener('DOMContentLoaded', function () {
        new Tornpaper({
            filterName: "filter_tornpaper",
            seed: 5,
            tornFrequency: 0.04,
            tornScale: 20,
            grungeFrequency: 0.02,
            grungeScale: 2
        });
    });
</script>