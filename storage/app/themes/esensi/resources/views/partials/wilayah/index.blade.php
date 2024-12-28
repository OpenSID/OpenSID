@extends('theme::template')

@section('layout')
    <div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <div class="lg:w-1/3 w-full">
            @include('theme::partials.statistik.sidenav')
        </div>
        <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            <div class="breadcrumb">
                <ol>
                    <li><a href="<?= site_url() ?>">Beranda</a></li>
                    <li>Data Statistik</li>
                </ol>
            </div>
            <h1 class="text-h2">{{ $heading }}</h1>

            <div class="table-responsive content py-3">
                <table class="w-full text-sm" id="tabelData">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th colspan="8">Wilayah / Ketua</th>
                            <th class="text-center">KK</th>
                            <th class="text-center">L+P</th>
                            <th class="text-center">L</th>
                            <th class="text-center">P</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var tabelData = $('#tabelData');
            var wilayahHTML = '';

            function loadWilayah() {

                var routeWilayah = '{{ route('api.wilayah.administratif') }}';

                $.get(routeWilayah, function(response) {

                    var wilayah = response.data;

                    tabelData.find('tbody').empty();
                    tabelData.find('tfoot').empty();

                    if (!wilayah.length) {
                        tabelData.find('tbody').append('<tr><td colspan="13" class="text-center">Tidak ada data wilayah yang tersedia</td></tr>');
                        return;
                    }

                    loadDusun(wilayah);
                });
            }

            // Tingkat 1 : Dusun
            function loadDusun(data) {
                let no = 1;
                let totalKK = 0;
                let totalPriaWanita = 0;
                let totalPria = 0;
                let totalWanita = 0;

                data.forEach(function(item, index) {
                    var row = `<tr>
                    <td class="text-center">${no}</td>
                    <td colspan="8">${item.attributes.sebutan_dusun + ' ' + item.attributes.dusun + item.attributes.kepala_nama}</td>
                    <td class="text-right">${item.attributes.keluarga_aktif_count}</td>
                    <td class="text-right">${item.attributes.penduduk_pria_wanita_count}</td>
                    <td class="text-right">${item.attributes.penduduk_pria_count}</td>
                    <td class="text-right">${item.attributes.penduduk_wanita_count}</td>
                </tr>`;

                    wilayahHTML += row;
                    totalKK += item.attributes.keluarga_aktif_count;
                    totalPriaWanita += item.attributes.penduduk_pria_wanita_count;
                    totalPria += item.attributes.penduduk_pria_count;
                    totalWanita += item.attributes.penduduk_wanita_count;
                    no++;

                    loadRW(item.attributes.rws);
                });

                tabelData.find('tbody').append(wilayahHTML);

                let totalPW = totalPria + totalWanita;
                var tfoot = `<tr class="font-bold">
                <td class="text-center" colspan="9">TOTAL</td>
                <td class="text-right">${totalKK}</td>
                <td class="text-right">${totalPW}</td>
                <td class="text-right">${totalPria}</td>
                <td class="text-right">${totalWanita}</td>
            </tr>`;

                tabelData.find('tbody').after(tfoot);
            }

            // Tingkat 2 : RW
            function loadRW(data) {
                let no = 1;

                data.forEach(function(item) {
                    if (item.rw !== '-') {
                        let row = `
                        <tr>
                            <td></td>
                            <td class="text-center">${no}</td>
                            <td colspan="7">${item.sebutan_rw + ' ' + item.rw + item.kepala_nama}</td>
                            <td class="text-right">${item.keluarga_aktif_count}</td>
                            <td class="text-right">${item.penduduk_pria_wanita_count}</td>
                            <td class="text-right">${item.penduduk_pria_count}</td>
                            <td class="text-right">${item.penduduk_wanita_count}</td>
                        </tr>`;

                        wilayahHTML += row;
                        no++;
                    }

                    loadRT(item.rw, item.rts);
                });
            }

            // Tingkat 3 : RT
            function loadRT(rw, data) {
                let no = 1;

                data.forEach(function(item) {
                    if (rw == item.rw && item.rt !== '-') {
                        let row = `
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-center">${no}</td>
                            <td colspan="6">${item.sebutan_rt + ' ' + item.rt + item.kepala_nama}</td>
                            <td class="text-right">${item.keluarga_aktif_count}</td>
                            <td class="text-right">${item.penduduk_pria_wanita_count}</td>
                            <td class="text-right">${item.penduduk_pria_count}</td>
                            <td class="text-right">${item.penduduk_wanita_count}</td>
                        </tr>`;

                        wilayahHTML += row;
                        no++;
                    }
                });
            }

            loadWilayah();
        });
    </script>
@endpush
