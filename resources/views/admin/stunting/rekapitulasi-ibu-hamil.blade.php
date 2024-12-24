@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>3 Bulanan Ibu Hamil</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">3 Bulanan Ibu Hamil</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header">
                    <div class="col-md-8 no-padding">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="kuartal" id="kuartal" required class="form-control input-sm" title="Pilih salah satu">
                                    @foreach (kuartal2() as $item)
                                        <option value="{{ $item['ke'] }}" {{ $item['ke'] == $kuartal ? 'selected' : '' }}>Kuartal ke
                                            {{ $item['ke'] }} ({{ $item['bulan'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="tahun" id="tahun" required class="form-control input-sm" title="Pilih salah satu">
                                    @foreach ($dataTahun as $item)
                                        <option value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="id" id="id" required class="form-control input-sm" title="Pilih salah satu">
                                    <option value="">Semua</option>
                                    @foreach ($posyandu as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $id ? 'selected' : '' }}>
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 no-padding">
                            <button type="button" class="btn btn-social btn-info btn-sm" id="cari">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding pull-right">
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table id="table-datas" class="table  table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">No</th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">NO KIA</th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama Ibu</th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">Status Kehamilan</th>
                                <th colspan="3" rowspan="2" class="text-center" style="vertical-align: middle;">Tingkat
                                    Konvergensi Indikator</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-center" style="vertical-align: middle;">Usia Kehamilan dan
                                    Persalinan</th>
                                <th colspan="8" class="text-center" style="vertical-align: middle;">Status Penerimaan
                                    Indikator</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align: middle;">Usia Kehamilan (Bulan)</th>
                                <th class="text-center" style="vertical-align: middle;">Tanggal Melahirkan</th>
                                <th class="text-center" style="vertical-align: middle;">Pemeriksaan Kehamilan (bulan)
                                </th>
                                <th class="text-center" style="vertical-align: middle;">Dapat & Konsumsi Pil Fe</th>
                                <th class="text-center" style="vertical-align: middle;">Pemeriksaan Nifas</th>
                                <th class="text-center" style="vertical-align: middle;">Konseling Gizi (Kelas IH)</th>
                                <th class="text-center" style="vertical-align: middle;">Kunjungan Rumah</th>
                                <th class="text-center" style="vertical-align: middle;">Kepemilikan Akses Air Bersih</th>
                                <th class="text-center" style="vertical-align: middle;">Kepemilikan jamban</th>
                                <th class="text-center" style="vertical-align: middle;">Jaminan Kesehatan</th>
                                <th class="text-center" style="vertical-align: middle;">Jumlah Diterima Lengkap</th>
                                <th class="text-center" style="vertical-align: middle;">Jumlah Seharusnya</th>
                                <th class="text-center" style="vertical-align: middle;">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$dataFilter)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;" colspan="17">Data Tidak
                                        Ditemukan!</td>
                                </tr>
                            @else
                                @foreach ($dataFilter as $item)
                                    {{-- {{ die(json_encode($item[1]['no_kia'])) }} --}}
                                    <tr>
                                        <td class="text-center" style="vertical-align: middle;">{{ $loop->iteration }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['no_kia'] }}</td>
                                        <td style="vertical-align: middle;">{{ $item['user']['nama_ibu'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['status_kehamilan'] = $item['user']['status_kehamilan'] == 1 ? 'NORMAL' : ($item['user']['status_kehamilan'] == 2 ? 'RISTI' : ($item['user']['status_kehamilan'] == 3 ? 'KEK' : '-')) }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['usia_kehamilan'] ?? '-' }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['user']['tanggal_melahirkan'] == '-' ? $item['user']['tanggal_melahirkan'] : tgl_indo($item['user']['tanggal_melahirkan']) }}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['periksa_kehamilan'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['pil_fe'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['pemeriksaan_nifas'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['konseling_gizi'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['kunjungan_rumah'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['akses_air_bersih'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['kepemilikan_jamban'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['indikator']['jaminan_kesehatan'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['konvergensi_indikator']['jumlah_diterima_lengkap'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['konvergensi_indikator']['jumlah_seharusnya'] }}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            {{ $item['konvergensi_indikator']['persen'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        @if ($dataFilter)
                            <tfoot>
                                <tr>
                                    <th colspan="3" rowspan="3" class="text-center" style="vertical-align: middle;">
                                        Tingkat Capaian Konvergensi</th>
                                    <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah Diterima
                                    </th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['periksa_kehamilan']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pil_fe']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pemeriksaan_nifas']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['konseling_gizi']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kunjungan_rumah']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['akses_air_bersih']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kepemilikan_jamban']['Y'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['jaminan_kesehatan']['Y'] }}</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">
                                        {{ $tingkatKonvergensiDesa['jumlah_diterima'] }}</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">
                                        {{ $tingkatKonvergensiDesa['jumlah_seharusnya'] }}</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">
                                        {{ $tingkatKonvergensiDesa['persen'] }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah
                                        Seharusnya</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['periksa_kehamilan']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pil_fe']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pemeriksaan_nifas']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['konseling_gizi']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kunjungan_rumah']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['akses_air_bersih']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kepemilikan_jamban']['jumlah_seharusnya'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['jaminan_kesehatan']['jumlah_seharusnya'] }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-center" style="vertical-align: middle;">%</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['periksa_kehamilan']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pil_fe']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['pemeriksaan_nifas']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['konseling_gizi']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kunjungan_rumah']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['akses_air_bersih']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['kepemilikan_jamban']['persen'] }}</th>
                                    <th class="text-center" style="vertical-align: middle;">
                                        {{ $capaianKonvergensi['jaminan_kesehatan']['persen'] }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#cari').click(function() {
            let kuartal = $('#kuartal option:selected').val();
            let tahun = $('#tahun option:selected').val();
            let posyandu = $('#id option:selected').val();
            window.location.href = "{{ site_url('stunting/rekapitulasi_ibu_hamil/') }}" + kuartal + "/" + tahun +
                "/" + posyandu;
        });
    </script>
@endpush
