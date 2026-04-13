<div x-data="{ loading: true, onLoading() { setTimeout(() => { this.loading = false }, 500) } }" x-init="onLoading()">
    <div class="fixed inset-0 bg-gradient-to-br from-green-50 via-white to-green-50 z-[9999] flex flex-col justify-center items-center" 
         x-show="loading" 
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        

        <!-- Modern Spinner -->
        <div class="relative mb-6">
            <!-- Outer Ring -->
            <div class="w-16 h-16 border-4 border-green-100 rounded-full animate-spin-slow">
                <div class="absolute inset-0 border-4 border-transparent border-t-green-600 rounded-full animate-spin"></div>
            </div>
            
            <!-- Inner Dot -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-15 h-8 flex items-center justify-center mt-1">
                        <figure>
                            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']) }}" class="h-10 mx-auto pb-2">
                        </figure>
                    </div>
            </div>
        </div>

        <!-- Loading Text -->
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}</h3>
            <p class="text-sm text-gray-600">Mohon tunggu sebentar...</p>
        </div>

        <!-- Progress Dots -->
        <div class="flex space-x-2 mt-6">
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
            <div class="w-2 h-2 bg-green-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
        </div>
    </div>
</div>
