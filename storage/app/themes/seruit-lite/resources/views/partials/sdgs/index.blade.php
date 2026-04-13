@extends('theme::layouts.full-content')

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">SDGs Desa</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">SDGs Desa</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    
    <div id="sdgs-container" class="mt-8">
        <div id="sdgs-loading" class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="mt-2 text-sm font-semibold">Memuat Data SDGs...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const apiUrl = `{{ route('api.sdgs') }}`;
        const container = document.getElementById('sdgs-container');

        fetch(apiUrl)
            .then(response => {
                if (!response.ok) throw new Error('Gagal mengambil data dari server.');
                return response.json();
            })
            .then(data => {
                if (data.error_msg || !data.data || data.data.length === 0) {
                    throw new Error(data.error_msg || 'Data SDGs tidak ditemukan.');
                }
                renderContent(data.data[0].attributes);
            })
            .catch(error => {
                console.error("Error fetching SDGs data:", error);
                container.innerHTML = `<div class="alert alert-danger text-center">${error.message}</div>`;
            });

        function renderContent(data) {
            const { data: sdgsData, average } = data;
            const path = `{{ base_url('assets/images/sdgs/') }}`;
            
            let cardsHtml = '';
            sdgsData.forEach(item => {
                cardsHtml += `
                    <div class="bg-white dark:bg-gray-800/50 shadow-lg border border-gray-200 dark:border-gray-700 flex items-center p-4 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                        <div class="flex-shrink-0 w-20 h-20 lg:w-24 lg:h-24">
                            <img class="w-full h-full object-contain" src="${path}${item.image}" alt="${escapeHtml(item.name)}">
                        </div>
                        <div class="ml-4 flex-grow">
                            <p class="text-xs text-gray-500 dark:text-gray-400">SDG #${item.id}</p>
                            <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-gray-100 line-clamp-2">${escapeHtml(item.name)}</h3>
                            <div class="mt-2">
                                <span class="text-2xl lg:text-3xl font-bold text-blue-600 dark:text-blue-400">${item.score}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">/ 100</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = `
                <div class="mb-8 text-center bg-gray-50 dark:bg-gray-900/50 p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-base text-gray-600 dark:text-gray-400 uppercase tracking-wider">Skor Total Rata-Rata SDGs Desa</p>
                    <p class="text-5xl lg:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-teal-400 dark:from-blue-400 dark:to-teal-300 my-2">
                        ${average}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    ${cardsHtml}
                </div>
            `;
        }
        
        function escapeHtml(text) {
            if (typeof text !== 'string') return '';
            return text.replace(/[&<>"']/g, m => ({'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'})[m]);
        }
    });
</script>
@endpush