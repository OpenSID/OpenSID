<div id="desa-ai-chat-widget" class="fixed flex flex-col items-end justify-end"
    style="z-index:9999; bottom:65px; right:24px;">


    <!-- Chat Window -->
    <div id="chat-window"
        class="bg-white rounded-2xl shadow-2xl w-[90vw] sm:w-[380px] flex flex-col transition-all duration-300 transform translate-y-4 opacity-0 pointer-events-auto hidden border border-gray-200 overflow-hidden mb-2 ml-2"
        style="height: min(60vh, calc(100vh - 120px));">

        <!-- Header -->
        <div class="bg-green-700 p-4 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <img src="{{ theme_asset('icons/ai-icon.png') }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">AI Asisten {{ ucfirst(setting('sebutan_desa')) }}
                        {{ ucwords($desa['nama_desa']) }}
                    </h3>
                    <div class="flex text-white items-center gap-2 mt-0.5">
                        <div id="status-dot" class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></div>
                        <span id="status-text" class="text-white text-xs">Memeriksa koneksi...</span>
                    </div>
                </div>
            </div>
            <button id="close-chat"
                class="text-white hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- JDIH Search Mode Panel (Hidden by default) --}}
        <div id="jdih-search-panel"
            class="hidden flex-col bg-gradient-to-b from-green-50 to-white border-b border-gray-200">
            {{-- Mode Toggle Header --}}
            <div class="flex items-center justify-between p-3 bg-white/80 backdrop-blur-sm border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📚</span>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Pencarian Peraturan JDIH</h4>
                        <p class="text-xs text-gray-500">Database peraturan.bpk.go.id</p>
                    </div>
                </div>
                <button id="exit-jdih-mode"
                    class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1 rounded hover:bg-gray-100 transition">
                    ← Kembali ke Chat
                </button>
            </div>

            {{-- Search Form --}}
            <form id="jdih-search-form" class="p-3 space-y-3">
                {{-- Main Search --}}
                <div>
                    <label class="text-xs font-medium text-gray-600 mb-1 block">Kata Kunci</label>
                    <input type="text" name="keywords" id="jdih-keywords"
                        placeholder="Contoh: PERBUP PURBALINGGA, dana desa..."
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500 bg-white">
                </div>

                {{-- Advanced Filters Toggle --}}
                <button type="button" id="toggle-jdih-filters"
                    class="flex items-center gap-1 text-xs text-green-600 hover:text-green-700 font-medium">
                    <svg class="w-4 h-4 transition-transform" id="filter-arrow" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Filter Lanjutan
                </button>

                {{-- Advanced Filters (Hidden by default) --}}
                <div id="jdih-advanced-filters" class="hidden space-y-3 pt-2 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Tentang</label>
                            <input type="text" name="tentang" id="jdih-tentang" placeholder="Subjek peraturan..."
                                class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500 bg-white">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Nomor</label>
                            <input type="text" name="nomor" id="jdih-nomor" placeholder="10, 15..."
                                class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500 bg-white">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Tahun</label>
                            <input type="text" name="tahun" id="jdih-tahun" placeholder="2024,2025..."
                                class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500 bg-white">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Jenis</label>
                            <select name="jenis" id="jdih-jenis"
                                class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500/20 focus:border-green-500 bg-white">
                                <option value="">Semua Jenis</option>
                                <option value="23">Peraturan Bupati</option>
                                <option value="22">Peraturan Daerah</option>
                                <option value="24">Peraturan Gubernur</option>
                                <option value="3">Peraturan Menteri</option>
                                <option value="1">Undang-Undang</option>
                                <option value="2">Peraturan Pemerintah</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Search Button --}}
                <button type="submit" id="jdih-search-btn"
                    class="py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-medium text-sm rounded-lg transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari Peraturan
                </button>
            </form>
        </div>

        {{-- JDIH Results Container (replaces messages in JDIH mode) --}}
        <div id="jdih-results-container" class="hidden flex-1 overflow-y-auto p-4 bg-gray-50 min-h-0">
            {{-- Results will be injected here --}}
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 scroll-smooth min-h-0">
            <!-- Welcome Message -->
            <div class="flex gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border border-green-200 overflow-hidden"
                    style="width: 32px; height: 32px; min-width: 32px; min-height: 32px; flex-shrink: 0;">
                    <img src="{{ theme_asset('icons/ai-icon.png') }}" class="w-full h-full shadow-sm object-cover"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 max-w-[85%]">
                    <p class="text-sm text-gray-700">Halo! Saya asisten virtual {{ ucfirst(setting('sebutan_desa')) }}
                        {{ ucwords($desa['nama_desa']) }}. Ada yang bisa saya bantu terkait
                        informasi desa?
                    </p>
                    <p class="text-xs text-gray-500 mt-2">💡 <em>Tip: Ketik "cari peraturan" untuk membuka pencarian
                            JDIH</em></p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100 shrink-0">
            <form id="chat-form" class="relative flex items-end gap-2">
                <textarea id="chat-input" rows="1" placeholder="Ketik pertanyaan Anda..."
                    class="w-full py-2.5 pl-4 pr-10 bg-gray-50 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 text-sm resize-none max-h-24 scrollbar-hide text-gray-700"
                    required></textarea>
                {{-- JDIH Search Button --}}
                <button type="button" id="open-jdih-btn" title="Cari Peraturan JDIH"
                    class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full transition-colors shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </button>
                <button type="submit" id="send-btn"
                    class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-full transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-sm shrink-0">
                    <svg id="send-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <svg id="stop-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 24 24">
                        <rect x="6" y="6" width="12" height="12" rx="2" />
                    </svg>
                </button>
            </form>
            <div class="text-center mb-16 mt-2">
                <a href="https://opendesa.id/tema-pro-opensid/" target="_blank"
                    class="text-[10px] text-green-400">Didukung oleh Tema Perwira</a>
            </div>
        </div>
    </div>

    <!-- AI Welcome Message Tooltip -->
    <div id="ai-chat-tooltip"
        class="mb-4 mr-4 bg-white text-gray-800 text-sm p-4 rounded-xl shadow-lg border border-green-100 max-w-[280px] relative transform transition-all duration-500 opacity-0 translate-y-4 hidden origin-bottom-right pointer-events-auto">
        <div class="flex items-start gap-3">
            <div
                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0 border border-green-200">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-900">Halo saya AI Asisten {{ ucfirst(setting('sebutan_desa')) }}
                    {{ ucwords($desa['nama_desa']) }}
                </p>
                <p class="text-xs text-gray-600 mt-1">Kamu bisa tanya apa saja disini.</p>
            </div>
            <button onclick="document.getElementById('ai-chat-tooltip').remove()"
                class="text-gray-400 hover:text-gray-600 -mt-1 -mr-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <!-- Arrow -->
        <div class="absolute -bottom-2 right-8 w-4 h-4 bg-white border-b border-r border-green-100 transform rotate-45">
        </div>
    </div>

    <!-- Toggle Button -->
    <button id="toggle-chat"
        class="group mb-4 p-3 rounded-full transition-all duration-300 pointer-events-auto flex items-center justify-center gap-2 relative overflow-hidden animate-pulse-green">
        <span
            class="absolute hover:scale-105 inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
        <img src="{{ theme_asset('icons/ai-icon.png') }}"
            class="w-10 h-10 relative z-10 object-cover rounded-full animate-tilt">
        <span
            class="font-semibold text-sm pr-1 relative z-10 hidden group-hover:block transition-all duration-300">Chat</span>
    </button>
</div>


<!-- Load AI Chat CSS -->
<link rel="stylesheet" href="{{ theme_asset('css/ai_chat.css') }}">
<!-- Load Marked.js for Markdown rendering -->
<script src="https://cdn.jsdelivr.net/npm/marked@11.1.1/marked.min.js"></script>
<!-- Load AI Chat Scripts in correct order -->
<script src="{{ theme_asset('js/ai_chat.js') }}"></script>
<script src="{{ theme_asset('js/ai_chat_widget.js') }}"></script>
<!-- Load JDIH Search Handler -->
<script src="{{ theme_asset('js/jdih_search.js') }}"></script>
<!-- AI Chat Configuration -->
<script>
    window.DESA_AI_CONFIG = {
        iconUrl: '{{ theme_asset("icons/ai-icon.png") }}'
    };

    // AI Icon Animation Controller - Random spin then tilt
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            const icon = document.querySelector('#toggle-chat img');
            if (!icon) return;

            let spinCount = 0;
            let targetSpins = getRandomInt(2, 7);

            function getRandomInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function doSpin() {
                icon.classList.remove('animate-tilt-left', 'animate-tilt-right');
                icon.classList.add('animate-spin-y');
                spinCount++;
            }

            function doTilt() {
                icon.classList.remove('animate-spin-y');
                const direction = Math.random() > 0.5 ? 'animate-tilt-left' : 'animate-tilt-right';
                icon.classList.add(direction);
            }

            function animate() {
                if (spinCount < targetSpins) {
                    doSpin();
                    setTimeout(animate, 1500); // Wait for spin to complete
                } else {
                    doTilt();
                    // Reset after tilt and start new cycle
                    setTimeout(function () {
                        icon.classList.remove('animate-tilt-left', 'animate-tilt-right', 'animate-spin-y');
                        spinCount = 0;
                        targetSpins = getRandomInt(2, 7);
                        setTimeout(animate, 1000); // Pause before next cycle
                    }, 500);
                }
            }

            // Start animation after a short delay
            setTimeout(animate, 1000);
        });
    })();
</script>