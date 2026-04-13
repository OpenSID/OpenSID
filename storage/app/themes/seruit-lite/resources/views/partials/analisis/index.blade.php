<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

@extends('theme::layouts.full-content')

@push('styles')
<style>
    .pagination-link { @apply py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white disabled:opacity-50 disabled:cursor-not-allowed; border-radius: 0 !important; }
    .pagination-link.active { @apply z-10 text-blue-600 border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white; }
    .pagination-link.dots { @apply cursor-default; }
</style>
@endpush

@section('content')
<div x-data="analisisData()" class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Data Analisis Desa</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Agregasi Data Analisis</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    <div class="mt-8 mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
        <label for="filter-analisis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Analisis</label>
        <select id="filter-analisis" x-model="selectedMaster" @change="loadIndikator(1)" class="form-input w-full md:w-1/2 dark:bg-gray-700 dark:border-gray-600">
            <template x-for="master in masterList" :key="master.id">
                <option :value="master.id" x-text="`${master.attributes.master} (${master.attributes.tahun})`"></option>
            </template>
        </select>
    </div>
    <div x-show="selectedMaster" x-cloak class="mb-8 text-sm p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
        <h3 class="font-bold text-lg mb-2">Rincian Analisis</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div><strong class="text-gray-500 dark:text-gray-400">Pendataan:</strong> <span x-text="detailMaster.master"></span></div>
            <div><strong class="text-gray-500 dark:text-gray-400">Subjek:</strong> <span x-text="detailMaster.subjek"></span></div>
            <div><strong class="text-gray-500 dark:text-gray-400">Tahun:</strong> <span x-text="detailMaster.tahun"></span></div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <h3 class="font-bold text-lg mb-4">Daftar Indikator</h3>
        <table class="w-full text-sm border-collapse" id="tabel-indikator">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider w-12 border border-gray-300 dark:border-gray-600">No.</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Indikator</th>
                </tr>
            </thead>
            <tbody>
                <tr x-show="isLoading">
                    <td colspan="2" class="p-4 text-center border border-gray-300 dark:border-gray-600">
                        <div class="flex items-center justify-center space-x-2 text-gray-500">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memuat Indikator...</span>
                        </div>
                    </td>
                </tr>
                <template x-for="(item, index) in indikatorList" :key="item.id">
                    <tr class="border-b dark:border-gray-700">
                        <td class="p-2 text-center border border-gray-300 dark:border-gray-600" x-text="index + 1"></td>
                        <td class="p-2 whitespace-normal border border-gray-300 dark:border-gray-600">
                            <a :href="`{{ site_url('jawaban_analisis') }}?filter[id_indikator]=${item.id}&filter[subjek_tipe]=${item.attributes.subjek_tipe}&filter[id_periode]=${item.attributes.id_periode}`" 
                               class="font-semibold text-blue-600 hover:underline" x-text="item.attributes.indikator"></a>
                        </td>
                    </tr>
                </template>
                <tr x-show="!isLoading && indikatorList.length === 0">
                    <td colspan="2" class="p-4 text-center text-gray-500 border border-gray-300 dark:border-gray-600">Pilih salah satu analisis untuk menampilkan indikator.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="pagination-container" class="mt-8"></div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    function analisisData() {
        return {
            masterList: [],
            indikatorList: [],
            selectedMaster: null,
            detailMaster: {},
            isLoading: false,
            init() {
                this.loadMaster();
            },
            loadMaster() {
                fetch('{{ route('api.analisis.master') }}')
                    .then(res => res.json())
                    .then(data => {
                        this.masterList = data.data;
                        if (this.masterList.length > 0) {
                            this.selectedMaster = this.masterList[0].id;
                            this.updateDetail();
                            this.loadIndikator(1);
                        }
                    });
            },
            loadIndikator(page) {
                if (!this.selectedMaster) return;
                this.isLoading = true;
                this.updateDetail();
                fetch(`{{ route('api.analisis.indikator') }}?filter[id_master]=${this.selectedMaster}&page[number]=${page}&page[size]=10`)
                    .then(res => res.json())
                    .then(data => {
                        this.indikatorList = data.data;
                        document.getElementById('pagination-container').innerHTML = data.links;
                        this.isLoading = false;
                    });
            },
            updateDetail() {
                const master = this.masterList.find(m => m.id == this.selectedMaster);
                if (master) this.detailMaster = master.attributes;
            }
        };
    }
    document.addEventListener('DOMContentLoaded', function() {
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = new URL($(this).attr('href')).searchParams.get('page[number]');
            const alpine = document.querySelector('[x-data]').__x;
            alpine.$data.loadIndikator(page);
        });
    });
</script>
@endpush