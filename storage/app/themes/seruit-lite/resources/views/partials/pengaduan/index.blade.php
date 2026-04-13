<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

@extends('theme::layouts.full-content')

@section('content')
<div 
    x-data="{ 
        newPengaduanOpen: false, 
        detailOpen: false, 
        pengaduan: null,
        init() {
            @if((session('notif') && $errors->any()) || (session('notif') && session('notif')['status'] == -1))
                this.newPengaduanOpen = true;
            @endif

            document.addEventListener('open-detail-modal', (event) => {
                this.pengaduan = event.detail;
                this.detailOpen = true;
            });
        }
    }"
    class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">

    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Pengaduan</li>
        </ol>
    </nav>

    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Layanan Pengaduan</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="mt-8 mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center justify-between">
        <button @click="newPengaduanOpen = true" class="btn btn-primary w-full md:w-auto">
            <i class="fas fa-plus mr-2"></i> Buat Pengaduan Baru
        </button>
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <select id="filter-status" class="form-select w-full sm:w-48 text-sm bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white">
                <option value="">Semua Status</option>
                <option value="1">Menunggu Diproses</option>
                <option value="2">Sedang Diproses</option>
                <option value="3">Selesai Diproses</option>
            </select>
            <div class="relative w-full sm:w-64">
                <input type="text" id="filter-search" placeholder="Cari judul pengaduan..." class="form-input w-full pr-10 text-sm bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                <button id="btn-search" class="absolute inset-y-0 right-0 flex items-center justify-center w-10 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    @if(session('notif') && session('notif')['status'] == 1)
        <div class="alert alert-success mb-4">{{ session('notif')['pesan'] }}</div>
    @endif

    <div id="pengaduan-list" class="space-y-4"></div>

    <div class="mt-8 pagination-container flex justify-center"></div>

    <template x-teleport="body">
        <div x-show="newPengaduanOpen" x-cloak 
             class="fixed inset-0 z-[9999] overflow-y-auto" 
             style="z-index: 9999;" 
             role="dialog" aria-modal="true">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="newPengaduanOpen" 
                     @click="newPengaduanOpen = false" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" 
                     aria-hidden="true">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="newPengaduanOpen" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative z-[10000] inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl border border-gray-300 dark:border-gray-600 rounded-lg">
                    
                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Formulir Pengaduan Baru</h3>
                        <button @click="newPengaduanOpen = false" class="p-2 -m-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scrollbar">
                        <input type="hidden" name="{{ get_instance()->security->get_csrf_token_name() }}" value="{{ get_instance()->security->get_csrf_hash() }}">
                        
                        @if (session('notif') && $errors->any())
                            <div class="alert alert-danger text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @php $data = session('data', []); @endphp
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">NIK (Opsional)</label>
                            <input type="text" name="nik" maxlength="16" class="form-input w-full text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan 16 digit NIK" value="{{ e($data['nik'] ?? '') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap*</label>
                            <input type="text" name="nama" class="form-input w-full text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="Nama sesuai identitas" value="{{ e($data['nama'] ?? '') }}" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email (Opsional)</label>
                            <input type="email" name="email" class="form-input w-full text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="contoh@email.com" value="{{ e($data['email'] ?? '') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">No. Telepon/WA (Opsional)</label>
                            <input type="text" name="telepon" class="form-input w-full text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="08..." value="{{ e($data['telepon'] ?? '') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Judul Pengaduan*</label>
                            <input type="text" name="judul" class="form-input w-full text-sm font-bold bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="Ringkasan masalah" value="{{ e($data['judul'] ?? '') }}" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Isi Pengaduan*</label>
                            <textarea name="isi" class="form-textarea w-full text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="Jelaskan detail pengaduan Anda di sini..." rows="5" required>{{ e($data['isi'] ?? '') }}</textarea>
                        </div>
                        
                        <div>
                            <label for="foto-upload" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Lampiran Foto (Opsional)</label>
                            <input type="file" name="foto" id="foto-upload" accept="image/png, image/jpeg, image/jpg" class="form-input w-full text-xs p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-4 items-center bg-gray-100 dark:bg-gray-900 p-3 border dark:border-gray-700 rounded-sm">
                            <div class="flex items-center space-x-2">
                                <img id="captcha" src="{{ site_url('captcha') }}" alt="CAPTCHA" class="border border-gray-300 h-10 w-auto bg-white rounded">
                                <button type="button" class="p-2 bg-white dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded" title="Ganti Kode" onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <input type="text" name="captcha_code" class="form-input w-full md:w-auto flex-grow text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" placeholder="Kode Keamanan*" required>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900 -mx-6 -mb-6 p-4 mt-4 sticky bottom-0 z-10">
                            <button type="button" @click="newPengaduanOpen = false" class="btn bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white hover:bg-gray-400 dark:hover:bg-gray-500">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="detailOpen" x-cloak 
             class="fixed inset-0 z-[9999] overflow-y-auto" 
             style="z-index: 9999;" 
             role="dialog" aria-modal="true">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="detailOpen" 
                     @click="detailOpen = false" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" 
                     aria-hidden="true">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="detailOpen" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative z-[10000] inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl border border-gray-300 dark:border-gray-700 rounded-lg">
                    
                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="pengaduan?.attributes.judul"></h3>
                        <button @click="detailOpen = false" class="p-2 -m-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <div class="prose dark:prose-invert max-w-none">
                            <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-4 pb-4 border-b dark:border-gray-700">
                                <i class="fas fa-user-circle"></i>
                                <span x-text="pengaduan?.attributes.nama" class="font-semibold"></span>
                                <span>•</span>
                                <i class="fas fa-calendar-alt"></i>
                                <span x-text="pengaduan?.attributes.created_at"></span>
                            </div>
                            <p class="whitespace-pre-wrap text-gray-800 dark:text-gray-200 text-base leading-relaxed" x-text="pengaduan?.attributes.isi"></p>
                            <template x-if="pengaduan?.attributes.foto">
                                <figure class="mt-6">
                                    <a :href="pengaduan.attributes.foto" target="_blank">
                                        <img :src="pengaduan.attributes.foto" alt="Lampiran Pengaduan" class="w-full h-auto border-4 border-gray-100 dark:border-gray-700 rounded-sm">
                                    </a>
                                    <figcaption class="text-xs text-center mt-2 text-gray-500 dark:text-gray-400">Klik gambar untuk memperbesar</figcaption>
                                </figure>
                            </template>
                        </div>
                        
                        <div class="mt-8 pt-8 border-t dark:border-gray-700">
                            <h4 class="font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                                <i class="fas fa-reply-all mr-2 text-blue-500"></i> Tanggapan Resmi
                            </h4>
                            <div class="space-y-6">
                                <template x-if="pengaduan?.attributes.child.length > 0">
                                    <template x-for="tanggapan in pengaduan.attributes.child" :key="tanggapan.id">
                                        <div class="flex space-x-4 p-4 bg-blue-50/50 dark:bg-gray-900/50 border border-blue-100 dark:border-gray-700 rounded-sm">
                                            <div class="flex-shrink-0 w-10 h-10 bg-blue-500 flex items-center justify-center text-white shadow-lg">
                                                <i class="fas fa-user-shield"></i>
                                            </div>
                                            <div class="flex-grow">
                                                <div class="flex justify-between items-center mb-2">
                                                    <p class="font-bold text-sm text-blue-600 dark:text-blue-400" x-text="tanggapan.nama"></p>
                                                    <span class="text-[10px] text-gray-400" x-text="tanggapan.created_at"></span>
                                                </div>
                                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed" x-text="tanggapan.isi"></p>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="pengaduan?.attributes.child.length === 0">
                                    <div class="text-center py-6 bg-gray-50 dark:bg-gray-900/30 text-gray-500 dark:text-gray-400 italic text-sm border border-dashed dark:border-gray-700">
                                        Belum ada tanggapan untuk laporan ini.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        let allPengaduanData = [];
        const pengaduanList = document.getElementById('pengaduan-list');
        const paginationContainer = document.querySelector('.pagination-container');
        const statusFilter = document.getElementById('filter-status');
        const searchInput = document.getElementById('filter-search');
        const searchButton = document.getElementById('btn-search');
        const pageSize = 5;

        function loadPengaduan(pageNumber = 1, status = '', search = '') {
            let filters = [];
            if (status) filters.push(`filter[status]=${status}`);
            if (search) filters.push(`filter[search]=${search}`);
            
            const apiUrl = `{{ ci_route('internal_api.pengaduan') }}?sort=-created_at&page[number]=${pageNumber}&page[size]=${pageSize}&${filters.join('&')}`;
            
            pengaduanList.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center py-12"><svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="mt-2 text-sm font-semibold text-gray-600 dark:text-gray-400">Sinkronisasi Data...</p></div>`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    allPengaduanData = data.data;
                    displayPengaduan(data);
                    renderPagination(data.meta.pagination);
                })
                .catch(() => {
                    pengaduanList.innerHTML = `<div class="p-4 bg-red-100 border border-red-200 text-red-700 text-sm dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">Gagal mengambil data dari server.</div>`;
                });
        }

        window.showDetail = function(index) {
            const detailData = allPengaduanData[index];
            if (detailData) document.dispatchEvent(new CustomEvent('open-detail-modal', { detail: detailData }));
        };

        function displayPengaduan(data) {
            pengaduanList.innerHTML = '';
            if (!data.data || data.data.length === 0) {
                pengaduanList.innerHTML = `<div class="text-center py-12 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 text-gray-500">Tidak menemukan data pengaduan.</div>`;
                return;
            }

            data.data.forEach((item, index) => {
                const statusInfo = getStatusInfo(item.attributes.status);
                const card = `
                    <div onclick="showDetail(${index})" class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row gap-4 cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow-md group">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 ${statusInfo.bgColor} ${statusInfo.textColor} shadow-inner transition-transform group-hover:scale-110">
                            <i class="fas ${statusInfo.icon} text-xl"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-gray-800 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">${escapeHtml(item.attributes.judul)}</h4>
                                <span class="text-[9px] font-extrabold px-2 py-1 uppercase tracking-tighter ${statusInfo.bgColor} ${statusInfo.textColor}">${statusInfo.text}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-[10px] text-gray-400 mb-2">
                                <span>Oleh ${escapeHtml(item.attributes.nama)}</span>
                                <span>•</span>
                                <span>${item.attributes.created_at}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">${escapeHtml(item.attributes.isi)}</p>
                        </div>
                        <div class="flex-shrink-0 text-center sm:text-right flex sm:flex-col justify-center gap-2 sm:gap-0 border-t sm:border-t-0 sm:border-l dark:border-gray-700 pt-2 sm:pt-0 sm:pl-4">
                            <div class="font-bold text-xl text-blue-600 dark:text-blue-400">${item.attributes.child_count}</div>
                            <div class="text-[9px] font-bold uppercase text-gray-400 tracking-wider">Tanggapan</div>
                        </div>
                    </div>`;
                pengaduanList.insertAdjacentHTML('beforeend', card);
            });
        }
        
        function renderPagination(meta) {
            paginationContainer.innerHTML = '';
            
            if (meta.total_pages <= 1) return;

            let html = '<div class="inline-flex rounded-md shadow-sm" role="group">';
            
            if (meta.current_page > 1) {
                html += `<button onclick="window.changePage(${meta.current_page - 1})" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600">Sebelumnya</button>`;
            }
            
            html += `<span class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white">Halaman ${meta.current_page} dari ${meta.total_pages}</span>`;
            
            if (meta.current_page < meta.total_pages) {
                html += `<button onclick="window.changePage(${meta.current_page + 1})" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600">Selanjutnya</button>`;
            }
            
            html += '</div>';
            paginationContainer.innerHTML = html;
        }

        window.changePage = function(page) {
            loadPengaduan(page, statusFilter.value, searchInput.value);
        }

        function getStatusInfo(status) {
            switch (status) {
                case 1: return { text: 'Menunggu', icon: 'fa-clock', bgColor: 'bg-yellow-100 dark:bg-yellow-900/50', textColor: 'text-yellow-800 dark:text-yellow-300' };
                case 2: return { text: 'Diproses', icon: 'fa-sync-alt', bgColor: 'bg-blue-100 dark:bg-blue-900/50', textColor: 'text-blue-800 dark:text-blue-300' };
                case 3: return { text: 'Selesai', icon: 'fa-check-circle', bgColor: 'bg-green-100 dark:bg-green-900/50', textColor: 'text-green-800 dark:text-green-300' };
                default: return { text: 'Unknown', icon: 'fa-question-circle', bgColor: 'bg-gray-100 dark:bg-gray-700', textColor: 'text-gray-800 dark:text-gray-300' };
            }
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return '';
            const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
            return text.replace(/[&<>"']/g, m => map[m]);
        }
        
        const applyFilter = () => loadPengaduan(1, statusFilter.value, searchInput.value);
        searchButton.addEventListener('click', applyFilter);
        statusFilter.addEventListener('change', applyFilter);
        searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') applyFilter(); });

        loadPengaduan();
    });
</script>
@endpush