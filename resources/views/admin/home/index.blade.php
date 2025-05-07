<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panggung Acara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white overflow-hidden">
    <!-- Container Utama -->
    <div class="relative w-full h-screen flex flex-col">
        <!-- Header (Opsional) -->
        <header class="bg-black bg-opacity-70 py-4 px-6 z-10">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">Nama Acara</h1>
                <div class="hidden md:block">
                    <span class="mr-4">Tanggal: 00/00/0000</span>
                    <span>Lokasi: Tempat Acara</span>
                </div>
            </div>
        </header>

        <!-- Area Panggung Utama -->
        <main class="flex-1 relative overflow-hidden">
            <!-- Panggung -->
            <div
                class="absolute inset-0 bg-gradient-to-b from-yellow-800 to-yellow-900 rounded-t-3xl md:rounded-t-[3rem] lg:rounded-t-[4rem] transform perspective-1000 rotate-x-5 origin-bottom">
                <!-- Dekorasi Panggung -->
                <div
                    class="absolute top-0 left-0 right-0 h-16 bg-red-700 rounded-t-3xl md:rounded-t-[3rem] lg:rounded-t-[4rem] flex items-center justify-center">
                    <div class="w-full h-2 bg-yellow-400"></div>
                </div>

                <!-- Lampu Sorot -->
                <div class="absolute top-0 left-1/4 w-16 h-16 bg-yellow-200 rounded-full filter blur-xl opacity-30">
                </div>
                <div class="absolute top-0 left-1/2 w-16 h-16 bg-blue-200 rounded-full filter blur-xl opacity-30"></div>
                <div class="absolute top-0 left-3/4 w-16 h-16 bg-red-200 rounded-full filter blur-xl opacity-30"></div>

                <!-- Area Konten Panggung -->
                <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center">
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-4 text-yellow-100 drop-shadow-lg">SELAMAT
                        DATANG</h2>
                    <p class="text-xl md:text-2xl lg:text-3xl text-yellow-200 mb-8">Di Acara Kami</p>
                    <div class="w-full max-w-2xl bg-black bg-opacity-40 p-6 rounded-lg backdrop-blur-sm">
                        <p class="text-lg md:text-xl">"Judul Acara atau Pesan Khusus"</p>
                    </div>
                </div>

                <!-- Tangga Panggung (Opsional) -->
                <div
                    class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full w-64 h-12 bg-yellow-800 rounded-b-lg">
                    <div class="absolute top-0 left-0 right-0 h-4 bg-yellow-700 rounded-b-lg"></div>
                </div>
            </div>

            <!-- Penonton/Background (Opsional) -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/70 to-transparent z-0"></div>
        </main>

        <!-- Footer (Opsional) -->
        <footer class="bg-black bg-opacity-70 py-3 px-6 z-10">
            <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm mb-2 md:mb-0">© 2023 Nama Organisasi</div>
                <div class="flex space-x-4">
                    <button class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 rounded">Info</button>
                    <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded">Kontak</button>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>