@extends('theme::layouts.full-content')

@section('content')
<div 
    x-data="wilayahData()" 
    x-init="loadData"
    class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10"
>
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $heading }}</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">{{ $heading }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="overflow-x-auto mt-8">
        <table class="w-full text-sm border-collapse" id="tabel-wilayah">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Wilayah / Ketua</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-20 border border-gray-300 dark:border-gray-600">KK</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-20 border border-gray-300 dark:border-gray-600">Jiwa</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-20 border border-gray-300 dark:border-gray-600">L</th>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-20 border border-gray-300 dark:border-gray-600">P</th>
                </tr>
            </thead>
            <tbody x-show="isLoading || wilayahData.length === 0">
                <tr x-show="isLoading">
                    <td colspan="5" class="p-8 text-center border border-gray-300 dark:border-gray-600">
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memuat Data Wilayah...</span>
                        </div>
                    </td>
                </tr>
                <tr x-show="!isLoading && wilayahData.length === 0">
                    <td colspan="5" class="p-4 text-center text-gray-500 border border-gray-300 dark:border-gray-600">Data wilayah tidak tersedia.</td>
                </tr>
            </tbody>
            <template x-for="(dusun, dusunIndex) in wilayahData" :key="dusun.id">
                <tbody>
                    <tr @click="toggleDusun(dusun.id)" class="bg-gray-100 dark:bg-gray-700/50 font-semibold cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-700">
                        <td class="p-3 flex items-center space-x-3 border border-gray-300 dark:border-gray-600">
                            <i class="fas fa-chevron-right text-xs text-gray-400 transition-transform duration-300" :class="{ 'rotate-90': isOpen(dusun.id) }"></i>
                            <span x-text="`${dusun.attributes.sebutan_dusun} ${dusun.attributes.dusun} - ${dusun.attributes.kepala_nama}`"></span>
                        </td>
                        <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="dusun.attributes.keluarga_aktif_count"></td>
                        <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="dusun.attributes.penduduk_pria_wanita_count"></td>
                        <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="dusun.attributes.penduduk_pria_count"></td>
                        <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="dusun.attributes.penduduk_wanita_count"></td>
                    </tr>
                    <tr x-show="isOpen(dusun.id)" x-transition x-cloak>
                        <td colspan="5" class="p-0 border-l border-r border-gray-300 dark:border-gray-600">
                            <div class="pl-6 py-2">
                                <template x-for="(rw, rwIndex) in dusun.attributes.rws" :key="rw.rw">
                                    <div>
                                        <template x-if="rw.rw !== '-'">
                                            <div class="flex items-center py-2 font-semibold">
                                                <div class="flex-1 pl-4" x-text="`${rw.sebutan_rw} ${rw.rw} - ${rw.kepala_nama}`"></div>
                                                <div class="w-20 text-right pr-3" x-text="rw.keluarga_aktif_count"></div>
                                                <div class="w-20 text-right pr-3" x-text="rw.penduduk_pria_wanita_count"></div>
                                                <div class="w-20 text-right pr-3" x-text="rw.penduduk_pria_count"></div>
                                                <div class="w-20 text-right pr-3" x-text="rw.penduduk_wanita_count"></div>
                                            </div>
                                        </template>
                                        <template x-for="(rt, rtIndex) in rw.rts" :key="rt.rt">
                                            <template x-if="rt.rt !== '-' && rt.rw === rw.rw">
                                                <div class="flex items-center py-1 text-sm text-gray-700 dark:text-gray-300">
                                                    <div class="flex-1 pl-12" x-text="`${rt.sebutan_rt} ${rt.rt} - ${rt.kepala_nama}`"></div>
                                                    <div class="w-20 text-right pr-3" x-text="rt.keluarga_aktif_count"></div>
                                                    <div class="w-20 text-right pr-3" x-text="rt.penduduk_pria_wanita_count"></div>
                                                    <div class="w-20 text-right pr-3" x-text="rt.penduduk_pria_count"></div>
                                                    <div class="w-20 text-right pr-3" x-text="rt.penduduk_wanita_count"></div>
                                                </div>
                                            </template>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </template>
            <tfoot class="bg-gray-100 dark:bg-gray-700/50 font-bold">
                <tr>
                    <td class="p-3 text-left text-xs uppercase tracking-wider border border-gray-300 dark:border-gray-600">TOTAL</td>
                    <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="totals.kk"></td>
                    <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="totals.jiwa"></td>
                    <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="totals.laki"></td>
                    <td class="p-3 text-right border border-gray-300 dark:border-gray-600" x-text="totals.perempuan"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function wilayahData() {
        return {
            isLoading: true,
            wilayahData: [],
            totals: { kk: 0, jiwa: 0, laki: 0, perempuan: 0 },
            openDusun: [],

            toggleDusun(dusunId) {
                const index = this.openDusun.indexOf(dusunId);
                if (index === -1) {
                    this.openDusun.push(dusunId);
                } else {
                    this.openDusun.splice(index, 1);
                }
            },

            isOpen(dusunId) {
                return this.openDusun.includes(dusunId);
            },

            loadData() {
                this.isLoading = true;
                const apiUrl = `{{ route('api.wilayah.administratif') }}`;
                
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        this.wilayahData = data.data;
                        this.calculateTotals();
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error("Error fetching wilayah data:", error);
                        this.isLoading = false;
                        document.querySelector('#tabel-wilayah tbody').innerHTML = `<tr><td colspan="5" class="p-4 text-center text-red-500">Gagal memuat data wilayah.</td></tr>`;
                    });
            },

            calculateTotals() {
                let totalKK = 0, totalJiwa = 0, totalLaki = 0, totalPerempuan = 0;
                
                this.wilayahData.forEach(dusun => {
                    totalKK += dusun.attributes.keluarga_aktif_count;
                    totalJiwa += dusun.attributes.penduduk_pria_wanita_count;
                    totalLaki += dusun.attributes.penduduk_pria_count;
                    totalPerempuan += dusun.attributes.penduduk_wanita_count;
                });

                this.totals = {
                    kk: totalKK,
                    jiwa: totalJiwa,
                    laki: totalLaki,
                    perempuan: totalPerempuan
                };
            }
        };
    }
</script>
@endpush