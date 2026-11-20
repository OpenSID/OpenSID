<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Dalam Perbaikan - {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}</title>
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    
    @include('theme::commons.source_css')
    
    <style>
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes spin-slow-reverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 12s linear infinite;
        }
        .animate-spin-reverse {
            animation: spin-slow-reverse 15s linear infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-900 text-gray-200 min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 z-0"></div>
    <div class="absolute inset-0 bg-repeat bg-center opacity-10 pointer-events-none z-0"
         style="background-image: url('{{ theme_asset('images/lampung.webp') }}');">
    </div>

    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-600/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

    <div class="relative z-10 w-full max-w-3xl p-4">
        <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl rounded-none overflow-hidden">
            
            <div class="text-center pt-10 pb-6 px-6 relative">
                <div class="absolute top-4 right-4 text-white/10">
                    <i class="fas fa-cogs text-9xl animate-spin-slow"></i>
                </div>
                <div class="absolute bottom-4 left-4 text-white/5">
                    <i class="fas fa-cog text-7xl animate-spin-reverse"></i>
                </div>

                <div class="relative z-10 inline-block mb-6">
                    <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center p-2 mx-auto border-2 border-white/30 shadow-lg mb-4">
                        <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo Desa" class="w-full h-full object-contain">
                    </div>
                    <span class="px-3 py-1 bg-yellow-500/80 text-black text-xs font-bold uppercase tracking-wider rounded-sm">
                        Mode Perbaikan
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-pink-500 mb-2">
                    WEBSITE SEDANG DALAM PERBAIKAN
                </h1>
                <p class="text-lg text-gray-300 font-light">
                    Situs Resmi {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}
                </p>
            </div>

            <div class="h-px w-full bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

            <div class="p-8 md:p-10 space-y-8 text-center md:text-left">
                
                <div class="prose prose-invert max-w-none text-center">
                    <p class="text-gray-300 leading-relaxed">
                        Mohon maaf, saat ini kami sedang melakukan pemeliharaan sistem dan peningkatan performa untuk memberikan pelayanan digital yang lebih baik. Website akan segera kembali online.
                    </p>
                </div>

                <div class="bg-black/30 border border-white/10 p-6 rounded-none">
                    <h3 class="text-yellow-400 font-bold text-sm uppercase tracking-widest mb-4 text-center border-b border-white/10 pb-2">
                        Kontak Darurat & Pelayanan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 mb-2">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <span class="text-gray-400">Alamat Kantor</span>
                            <span class="font-semibold text-white mt-1 text-center">{{ $desa['alamat_kantor'] }}</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 mb-2">
                                <i class="fas fa-phone-alt text-lg"></i>
                            </div>
                            <span class="text-gray-400">Telepon</span>
                            <span class="font-semibold text-white mt-1">{{ $desa['telepon'] ?: '-' }}</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-pink-500/20 flex items-center justify-center text-pink-400 mb-2">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <span class="text-gray-400">Email</span>
                            <span class="font-semibold text-white mt-1">{{ $desa['email_desa'] ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-4">
                    <p class="text-sm text-gray-400 mb-6">Tertanda,</p>
                    <div class="inline-block border-b border-gray-500 pb-1 mb-1">
                        <p class="font-bold text-lg text-white">{{ $desa['nama_kepala_desa'] }}</p>
                    </div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                        {{ ucwords(setting('sebutan_kepala_desa') . ' ' . $desa['nama_desa']) }}
                        @if ($desa['nip_kepala_desa'])
                            <br>NIP. {{ $desa['nip_kepala_desa'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="bg-black/40 p-4 text-center text-xs text-gray-500 border-t border-white/5">
                &copy; {{ date('Y') }} {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }} - Tema Seruit {{ $themeVersion ?? 'v3.0.0' }}
            </div>
        </div>
    </div>
</body>
</html>