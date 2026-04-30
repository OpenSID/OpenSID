/**
 * JDIH Search Handler
 * Handles the JDIH (Jaringan Dokumentasi dan Informasi Hukum) search functionality
 */

document.addEventListener('DOMContentLoaded', function () {
    const jdihSearchForm = document.getElementById('jdih-search-form');
    const jdihResultsContainer = document.getElementById('jdih-results-container');
    const jdihSearchPanel = document.getElementById('jdih-search-panel');
    const chatMessages = document.getElementById('chat-messages');
    const toggleFiltersBtn = document.getElementById('toggle-jdih-filters');
    const advancedFilters = document.getElementById('jdih-advanced-filters');
    const filterArrow = document.getElementById('filter-arrow');

    if (!jdihSearchForm) return;

    // Make chat fullscreen on mobile
    function makeFullscreenOnMobile() {
        // Only apply on mobile devices (screen width < 640px)
        if (window.innerWidth >= 640) return;

        const chatWindow = document.getElementById('chat-window');
        if (!chatWindow) return;

        // Move to body to ensure true fullscreen (not relative to parent)
        document.body.appendChild(chatWindow);

        // Apply fullscreen styles directly
        chatWindow.style.position = 'fixed';
        chatWindow.style.top = '0';
        chatWindow.style.left = '0';
        chatWindow.style.width = '100vw';
        chatWindow.style.height = '100vh';
        chatWindow.style.zIndex = '999999'; // Extremely high z-index
        chatWindow.style.margin = '0';
        chatWindow.style.borderRadius = '0';
        chatWindow.style.maxWidth = '100%';
    }

    // Exit fullscreen on mobile
    function exitFullscreenOnMobile() {
        const chatWindow = document.getElementById('chat-window');
        const widgetContainer = document.getElementById('desa-ai-chat-widget');

        if (!chatWindow || !widgetContainer) return;

        // Move back to widget container
        widgetContainer.insertBefore(chatWindow, widgetContainer.firstChild);

        // Reset styles
        chatWindow.style.position = '';
        chatWindow.style.top = '';
        chatWindow.style.left = '';
        chatWindow.style.width = '';
        chatWindow.style.zIndex = '';
        chatWindow.style.margin = '';
        chatWindow.style.borderRadius = '';
        chatWindow.style.maxWidth = '';

        // Restore original dimensions
        chatWindow.style.height = 'min(80vh, calc(100vh - 120px))';
    }

    // Toggle advanced filters
    if (toggleFiltersBtn && advancedFilters && filterArrow) {
        toggleFiltersBtn.addEventListener('click', () => {
            const isHidden = advancedFilters.classList.contains('hidden');
            if (isHidden) {
                advancedFilters.classList.remove('hidden');
                filterArrow.style.transform = 'rotate(180deg)';
            } else {
                advancedFilters.classList.add('hidden');
                filterArrow.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Handle exit JDIH mode - restore chat
    const exitJdihBtn = document.getElementById('exit-jdih-mode');
    if (exitJdihBtn) {
        exitJdihBtn.addEventListener('click', () => {
            // Exit fullscreen on mobile
            exitFullscreenOnMobile();

            // Hide JDIH panel and results
            if (jdihSearchPanel) jdihSearchPanel.classList.add('hidden');
            if (jdihResultsContainer) jdihResultsContainer.classList.add('hidden');

            // Show chat messages
            if (chatMessages) chatMessages.classList.remove('hidden');

            // Clear the results
            if (jdihResultsContainer) jdihResultsContainer.innerHTML = '';

            // Reset the form
            if (jdihSearchForm) jdihSearchForm.reset();
        });
    }

    // Handle JDIH search form submission
    jdihSearchForm.addEventListener('submit', async function (e) {
        e.preventDefault(); // Prevent default form submission

        // Get form values
        const keywords = document.getElementById('jdih-keywords')?.value.trim() || '';
        const tentang = document.getElementById('jdih-tentang')?.value.trim() || '';
        const nomor = document.getElementById('jdih-nomor')?.value.trim() || '';
        const tahun = document.getElementById('jdih-tahun')?.value.trim() || '';
        const jenis = document.getElementById('jdih-jenis')?.value || '';

        // Build query parameters
        const params = new URLSearchParams();
        if (keywords) params.append('keywords', keywords);
        if (tentang) params.append('tentang', tentang);
        if (nomor) params.append('nomor', nomor);
        if (tahun) params.append('tahun', tahun);
        if (jenis) params.append('jenis', jenis);

        // Show loading state
        showLoading();

        // Hide chat messages, show results container
        if (chatMessages) chatMessages.classList.add('hidden');
        if (jdihResultsContainer) jdihResultsContainer.classList.remove('hidden');

        try {
            // Make API request to JDIH endpoint
            const apiUrl = `https://ai-assistant.digidesa.id/api/jdih/search?${params.toString()}`;
            const response = await fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data dari server');
            }

            const data = await response.json();
            displayResults(data);
            // Make fullscreen on mobile when results arrive
            makeFullscreenOnMobile();
        } catch (error) {
            console.error('JDIH Search Error:', error);
            showError(error.message);
        }
    });

    function showLoading() {
        if (!jdihResultsContainer) return;

        jdihResultsContainer.innerHTML = `
            <div class="flex items-center justify-center min-h-[400px]">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mb-4"></div>
                    <p class="text-gray-600 font-medium">Mencari peraturan...</p>
                </div>
            </div>
        `;
    }

    function showError(message) {
        if (!jdihResultsContainer) return;

        jdihResultsContainer.innerHTML = `
            <div class="flex items-center justify-center min-h-[400px]">
                <div class="text-center max-w-md px-4">
                    <svg class="w-16 h-16 mx-auto text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-700 font-semibold mb-2">Terjadi Kesalahan</p>
                    <p class="text-gray-500 text-sm">${message}</p>
                    <button onclick="document.getElementById('exit-jdih-mode').click()" 
                        class="mt-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                        Kembali ke Chat
                    </button>
                </div>
            </div>
        `;
    }

    function displayResults(response) {
        if (!jdihResultsContainer) return;

        // Check if response has data array
        if (!response || !response.data || response.data.length === 0) {
            jdihResultsContainer.innerHTML = `
                <div class="flex items-center justify-center min-h-[400px]">
                    <div class="text-center max-w-md px-4">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium mb-2">Tidak ada hasil ditemukan</p>
                        <p class="text-gray-400 text-sm">Coba ubah kata kunci pencarian Anda</p>
                    </div>
                </div>
            `;
            return;
        }

        const totalResults = response.count || response.data.length;
        const currentPage = response.meta?.current_page || 1;
        const totalPages = response.meta?.total_pages || 1;

        let html = `
            <div class="space-y-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">
                        Ditemukan ${totalResults} peraturan${totalPages > 1 ? ` (Halaman ${currentPage} dari ${totalPages})` : ''}
                    </h3>
                    <button onclick="document.getElementById('exit-jdih-mode').click()" 
                        class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1 rounded hover:bg-gray-100 transition">
                        ← Kembali
                    </button>
                </div>
        `;

        response.data.forEach((item, index) => {
            const title = item.judul || 'Tanpa Judul';
            const nomor = item.nomor || '-';
            const tahun = item.tahun || '-';
            const deskripsi = item.deskripsi || '';
            const detailUrl = item.detail_url || '#';
            const downloadUrl = item.download_url || '';
            const status = item.status || '';
            const tags = item.tags || [];

            // Extract regulation type from title (e.g., "Undang-undang (UU)", "Peraturan Pemerintah (PP)")
            const typeMatch = title.match(/^([^(]+)\(/);
            const jenis = typeMatch ? typeMatch[1].trim() : 'Peraturan';

            html += `
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="inline-block px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded">
                                    ${jenis}
                                </span>
                                ${nomor !== '-' ? `
                                    <span class="text-xs text-gray-500">
                                        No. ${nomor}${tahun !== '-' ? ` Tahun ${tahun}` : ''}
                                    </span>
                                ` : ''}
                                ${status ? `
                                    <span class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                                        ${status}
                                    </span>
                                ` : ''}
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">
                                ${title}
                            </h4>
                            ${deskripsi ? `<p class="text-xs text-gray-600 mb-3 line-clamp-3">${deskripsi}</p>` : ''}
                            ${tags.length > 0 ? `
                                <div class="flex flex-wrap gap-1 mb-3">
                                    ${tags.map(tag => `
                                        <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                                            ${tag}
                                        </span>
                                    `).join('')}
                                </div>
                            ` : ''}
                            <div class="flex items-center gap-3">
                                <a href="${detailUrl}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1 text-xs text-green-600 hover:text-green-700 font-medium">
                                    Lihat Detail
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                                ${downloadUrl ? `
                                    <a href="${downloadUrl}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 font-medium">
                                        Download PDF
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        jdihResultsContainer.innerHTML = html;
    }
});
