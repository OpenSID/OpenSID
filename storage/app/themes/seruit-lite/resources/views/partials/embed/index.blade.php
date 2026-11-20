@php
    $nama_desa = ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']);
    $menu_nama = $menu->nama ?? 'Konten Embed';
    $menu_link = $menu->link ?? site_url();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu_nama }} - {{ $nama_desa }}</title>
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    
    <link rel="stylesheet" href="{{ theme_asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-color-base);
        }
        #embedded-content {
            flex-grow: 1;
            border: none;
            width: 100%;
            height: 100%;
            display: none;
        }
        #loading-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            color: var(--text-color-base);
        }
    </style>
</head>
<body class="font-sans">
    
    <header class="bg-white dark:bg-gray-800 shadow-xl flex-shrink-0 z-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ site_url() }}" class="flex items-center space-x-3">
                    <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ $nama_desa }}" class="h-10 w-auto">
                    <span class="font-bold text-gray-800 dark:text-gray-100 hidden sm:block">{{ $nama_desa }}</span>
                </a>
                <a href="{{ site_url() }}" class="btn btn-primary text-sm rounded-none">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <main id="loading-indicator">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <p class="mt-3 font-semibold">Memuat Konten...</p>
    </main>
    
    <iframe id="embedded-content" src="{{ e($menu_link) }}" title="{{ e($menu_nama) }}"></iframe>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const iframe = document.getElementById('embedded-content');
            const loadingIndicator = document.getElementById('loading-indicator');

            if (document.documentElement.classList.contains('dark')) {
                try {
                    const url = new URL(iframe.src);
                    url.searchParams.append('theme', 'dark');
                    iframe.src = url.toString();
                } catch (e) {
                    console.warn("Could not modify iframe URL:", e);
                }
            }

            iframe.onload = function() {
                loadingIndicator.style.display = 'none';
                iframe.style.display = 'block';
            };
            
            iframe.onerror = function() {
                loadingIndicator.innerHTML = `
                    <div class="p-4 m-4">
                         <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p class="font-semibold">Konten tidak dapat dimuat.</p>
                            <p class="text-sm">Situs tujuan mungkin tidak mengizinkan untuk ditampilkan di halaman lain.</p>
                        </div>
                    </div>
                `;
            }
        });

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</body>
</html>