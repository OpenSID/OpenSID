@php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $palette = [
        'gradient' => 'from-green-500 to-teal-500', 
        'darker'   => 'from-green-600 to-teal-700', 
        'border'   => 'border-green-700', 
        'solid'    => 'bg-green-800'
    ];
    
    $_v1 = base64_decode('wqkg'); 
    $_v2 = base64_decode('IEhhayBDaXB0YSBEaWxpbmR1bmdpLg=='); 
    $_v3 = base64_decode('UGFydG5lciBEaWdpdGFsIA=='); 
    $_v4 = base64_decode('VXBkZXNh'); 
    $_v5 = base64_decode('aHR0cHM6Ly91cGRlc2EuY29tLw=='); 
    $_v6 = base64_decode('VGVtYSBTZXJ1aXQgTGl0ZQ=='); 
    $_v7 = base64_decode('aHR0cHM6Ly90ZW1hc2VydWl0LnVwZGVzYS5jb20v');
    $_v8 = base64_decode('T3BlblNJRA==');
    $_v9 = base64_decode('aHR0cHM6Ly9vcGVuZGVzYS5pZC8=');

    $social_icons = [
        'facebook'  => '<svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.3635 1.00006H2.63618C1.73209 1.00006 0.999817 1.73233 0.999817 2.63642V17.3637C0.999817 18.2678 1.73209 19.0001 2.63618 19.0001H10.818V11.6364H8.36345V9.18188H10.818V7.86379C10.818 5.36833 12.0338 4.27279 14.1079 4.27279C15.1012 4.27279 15.6265 4.34642 15.8752 4.37997V6.72733H14.4605C13.5802 6.72733 13.2725 7.19206 13.2725 8.13297V9.18188H15.8531L15.5029 11.6364H13.2725V19.0001H17.3635C18.2675 19.0001 18.9998 18.2678 18.9998 17.3637V2.63642C18.9998 1.73233 18.2667 1.00006 17.3635 1.00006Z"></path></svg>',
        'twitter'   => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'instagram' => '<svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6 1C3.239 1 1 3.239 1 6V14C1 16.761 3.239 19 6 19H14C16.761 19 19 16.761 19 14V6C19 3.239 16.761 1 14 1H6ZM16 3C16.552 3 17 3.448 17 4C17 4.552 16.552 5 16 5C15.448 5 15 4.552 15 4C15 3.448 15.448 3 16 3ZM10 5C12.761 5 15 7.239 15 10C15 12.761 12.761 15 10 15C7.239 15 5 12.761 5 10C5 7.239 7.239 5 10 5ZM10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10C7 10.7957 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7957 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7Z"></path></svg>',
        'youtube'   => '<svg class="w-5 h-5" viewBox="0 0 24 18" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.407-.501-9.407-.501s-7.537 0-9.407.501A3.007 3.007 0 0 0 .502 6.205a31.447 31.447 0 0 0-.502 4.795A31.447 31.447 0 0 0 .502 15.795a3.007 3.007 0 0 0 2.088 2.088c1.87.501 9.407.501 9.407.501s7.537 0 9.407-.501a3.007 3.007 0 0 0 2.088-2.088a31.447 31.447 0 0 0 .502-4.795a31.447 31.447 0 0 0-.502-4.795zM9.545 13.5V6.5l6.5 3.5-6.5 3.5z"/></svg>',
        'whatsapp'  => '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.011719 2C6.5057187 2 2.0234844 6.478375 2.0214844 11.984375C2.0204844 13.744375 2.4814687 15.462563 3.3554688 16.976562L2 22l5.2324219-1.236328C8.6914219 21.559672 10.333859 21.977516 12.005859 21.978516h.0039C17.514766 21.978516 21.995047 17.499141 21.998047 11.994141C22.000047 9.3251406 20.962172 6.8157344 19.076172 4.9277344C17.190172 3.0407344 14.683719 2.001 12.011719 2zm0 2c2.136 0 4.1433437.8327969 5.6523438 2.3417969C19.171344 7.8517969 20.000047 9.8581875 19.998047 11.992188c-.002 4.394-3.584234 7.976328-7.990234 7.976328-1.323 0-2.6434063-.334703-3.8066406-.969727l-.6738282-.367187-.4453125.675781-1.96875.464844.4804688-1.785156.2167969-.800782-.4140625-.71875C4.3898906 14.768562 4.0204844 13.387375 4.0214844 11.984375c.002-4.402 3.5832812-7.984375 7.9882816-7.984375zM8.4765625 7.375c-.167 0-.4370156.0625-.6660156.3125-.229.249-.875.8520781-.875 1.9800781s.9245313 2.347031 1.0495313 2.513672c.124.166 1.7266719 2.765625 4.2636719 3.765625 2.108 0.831 2.536141.667 2.994141.625.458-.041.458-.242187.396-1.328125s-1.079234-1.229188-1.225234-1.395188c-.146-.167-.291-.189453-.541-.064453s-.8438125.7615-1.0938125.9285c-.25.167-.416.189-.666-.036-.25-.225-1.0538125-.71875-2.0078125-1.56875-.742-.659-1.2426719-1.474609-1.3886719-1.724609-.145-.249-.015625-.385859.109375-.505859.112-.112.25-.29.375-.435.125-.145.17-.25.25-.415.08-.17.04-.315-.02-.435-.06-.12-.5555312-1.3325-.7655312-1.8325-.187-.415-.3845-.4246406-.5625-.4176406-.155.007-.3205625.0003594-.4865625.0003594z"/></svg>',
    ];
@endphp

<footer class="text-white mt-12" :class="darkMode ? 'bg-gray-900' : 'bg-gradient-to-r {{ $palette['gradient'] }}'">
    <div class="relative pt-16 pb-12 px-6" :class="darkMode ? 'bg-gray-800' : 'bg-gradient-to-r {{ $palette['darker'] }}'">
        <div class="absolute inset-0 z-0 bg-repeat bg-center opacity-10 dark:opacity-5 pointer-events-none" style="background-image: url('{{ theme_asset('images/lampung.webp') }}');"></div>
        <div class="absolute -top-8 left-1/2 -translate-x-1/2 w-full flex justify-center items-center px-4">
            <div class="h-px flex-grow" :class="darkMode ? 'bg-gray-700' : 'bg-white/20'"></div>
            <div class="w-16 h-16 rounded-full flex items-center justify-center border-4 p-1 mx-4 flex-shrink-0" :class="darkMode ? 'bg-gray-800 border-gray-700' : '{{ $palette['border'] }} bg-gradient-to-br {{ $palette['darker'] }}'">
                <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo" class="h-full w-full object-contain">
            </div>
            <div class="h-px flex-grow" :class="darkMode ? 'bg-gray-700' : 'bg-white/20'"></div>
        </div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-12 text-center pt-8 max-w-6xl mx-auto">
            <div>
                <h3 class="font-bold text-white uppercase tracking-wider text-base mb-4">Tentang</h3>
                <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-blue-100'">
                    Website Resmi {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }},
                    Kecamatan {{ ucwords($desa['nama_kecamatan']) }},
                    {{ ucwords(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']) }}.
                </p>
            </div>
            <div>
                <h3 class="font-bold text-white uppercase tracking-wider text-base mb-4">Alamat</h3>
                <p class="text-sm" :class="darkMode ? 'text-gray-300' : 'text-blue-100'">
                    {{ e($desa['alamat_kantor']) }}
                    @if (!empty($desa['email_desa']))
                        <br>Email: {{ e($desa['email_desa']) }}
                    @endif
                </p>
            </div>
            <div>
                <h3 class="font-bold text-white uppercase tracking-wider text-base mb-4">Media Sosial</h3>
                <div class="flex flex-wrap gap-3 justify-center">
                    @if ($sosmed)
                        @foreach ($sosmed as $data)
                            @if (!empty($data["link"]))
                                @php $ns = strtolower($data['nama']); @endphp
                                @if (isset($social_icons[$ns]))
                                    <a href="{{ $data['link'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full flex items-center justify-center text-white transition-all duration-300 hover:scale-110 hover:shadow-lg" :class="darkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-white/10 hover:bg-white/20'">
                                        <div class="w-5 h-5"><?= $social_icons[$ns] ?></div>
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-center text-xs py-6" :class="darkMode ? 'bg-gray-900 text-gray-500' : '{{ $palette['solid'] }} text-blue-100'">
        <div class="space-y-2">
            <p>
                {{ $_v1 . date('Y') }} {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}. {{ $_v2 }}
                <span class="mx-1">|</span>
                {{ $_v3 }} <a href="{{ $_v4 === 'Updesa' ? $_v5 : '#' }}" target="_blank" rel="dofollow" class="hover:text-white font-semibold">{{ $_v4 }}</a>
            </p>
            <p>
                <a href="{{ $_v9 }}" target="_blank" rel="noopener noreferrer" class="hover:text-white">{{ $_v8 }} {{ AmbilVersi() }}</a>
                <span class="mx-2">|</span>
                <a href="{{ $_v7 }}" target="_blank" rel="noopener noreferrer" class="hover:text-white">{{ $_v6 }} {{ $themeVersion ?? '3.0.0' }}</a>
            </p>
            <div class="pt-2">
                <a href="https://opendesa.id/tema-pro-opensid/" target="_blank" rel="noopener noreferrer" class="inline-block px-4 py-1.5 bg-yellow-400 text-gray-900 font-bold uppercase tracking-widest hover:bg-yellow-500 transition-colors shadow-sm" style="font-size: 9px;">
                    <i class="fas fa-shopping-cart mr-1"></i> Beli Seruit PRO
                </a>
            </div>
        </div>
    </div>
</footer>