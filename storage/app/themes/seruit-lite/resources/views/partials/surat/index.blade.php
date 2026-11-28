<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verifikasi Surat - {{ ucwords(setting('sebutan_desa') . ' ' . identitas('nama_desa')) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    
    {{-- Memuat aset CSS utama dari tema Seruit untuk konsistensi --}}
    <link rel="stylesheet" href="{{ theme_asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700">
            <img class="h-20 w-20 mx-auto mb-4" src="{{ gambar_desa(identitas('logo')) }}" alt="Logo Desa">
            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                Pemerintah {{ ucwords(setting('sebutan_kabupaten') . ' ' . identitas('nama_kabupaten')) }}
            </h1>
            <h2 class="text-lg text-gray-600 dark:text-gray-300">
                {{ ucwords(setting('sebutan_kecamatan') . ' ' . identitas('nama_kecamatan')) }}
            </h2>
            <h3 class="text-lg text-gray-600 dark:text-gray-300">
                {{ ucwords(setting('sebutan_desa') . ' ' . identitas('nama_desa')) }}
            </h3>
        </div>
        
        <div id="verification-message" class="p-6">
            {{-- Konten akan diisi oleh JavaScript --}}
        </div>
    </div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const messageContainer = document.getElementById('verification-message');
        
        const loadingHtml = `
            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p class="mt-3 font-semibold">Memverifikasi Data Surat...</p>
            </div>`;

        const notFoundHtml = `
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-300 flex items-center" role="alert">
                <i class="fas fa-times-circle fa-lg mr-3"></i>
                <div>
                    <span class="font-bold">Gagal!</span> Surat tidak ditemukan dalam sistem kami.
                </div>
            </div>`;

        const createSuccessHtml = (surat) => `
            <div class="space-y-4">
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 border-b dark:border-gray-600 pb-1">Menyatakan Bahwa:</h4>
                    <table class="w-full text-sm">
                        <tbody>
                            <tr><td class="w-1/3 py-1 font-semibold text-gray-600 dark:text-gray-400">Nomor Surat</td><td class="w-4">:</td><td class="text-gray-800 dark:text-gray-200">${surat.nomor_surat || '-'}</td></tr>
                            <tr><td class="py-1 font-semibold text-gray-600 dark:text-gray-400">Tanggal Surat</td><td>:</td><td class="text-gray-800 dark:text-gray-200">${surat.tanggal || '-'}</td></tr>
                            <tr><td class="py-1 font-semibold text-gray-600 dark:text-gray-400">Perihal</td><td>:</td><td class="text-gray-800 dark:text-gray-200">Surat ${surat.perihal || '-'}</td></tr>
                            <tr><td class="py-1 font-semibold text-gray-600 dark:text-gray-400">Atas Nama</td><td>:</td><td class="text-gray-800 dark:text-gray-200">${surat.nama_penduduk || '-'}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 border-b dark:border-gray-600 pb-1">Ditandatangani oleh:</h4>
                    <table class="w-full text-sm">
                        <tbody>
                            <tr><td class="w-1/3 py-1 font-semibold text-gray-600 dark:text-gray-400">Nama</td><td class="w-4">:</td><td class="text-gray-800 dark:text-gray-200">${surat.pamong_nama || '-'}</td></tr>
                            <tr><td class="py-1 font-semibold text-gray-600 dark:text-gray-400">Jabatan</td><td>:</td><td class="text-gray-800 dark:text-gray-200">${surat.pamong_jabatan || '-'}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-300 flex items-center mt-6" role="alert">
                    <i class="fas fa-check-circle fa-lg mr-3"></i>
                    <div>
                        <span class="font-bold">Terverifikasi!</span> Surat ini adalah benar dan tercatat dalam database sistem informasi kami.
                    </div>
                </div>
            </div>
        `;

        messageContainer.innerHTML = loadingHtml;

        fetch("{{ route('api.verifikasi-surat') }}?filter[id]={{ $id }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(response => {
                if (response.data && response.data.length > 0) {
                    const surat = response.data[0].attributes;
                    messageContainer.innerHTML = createSuccessHtml(surat);
                } else {
                    messageContainer.innerHTML = notFoundHtml;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                messageContainer.innerHTML = notFoundHtml;
            });
    });
</script>

</body>
</html>