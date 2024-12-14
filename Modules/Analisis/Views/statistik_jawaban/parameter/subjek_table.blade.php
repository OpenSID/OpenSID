@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Statistik Jawaban
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Analisis</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li class="active">Laporan Per Indikator</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ $cetak_action }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-print"></i> Cetak</a>
                    <a href="{{ $unduh_action }}" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-download"></i> Unduh</a>
                    <input type="hidden" name="params">
                    <a href="{{ ci_route('analisis_statistik_jawaban', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Laporan Per Indikator"><i
                            class="fa fa-arrow-circle-left "
                        ></i>Kembali Ke
                        Laporan Per Indikator</a>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                <tr>
                                    <td width="150">Indikator Pertanyaan</td>
                                    <td width="1">:</td>
                                    <td>{{ $analisis_statistik_pertanyaan['pertanyaan'] }}</td>
                                </tr>
                                <tr>
                                    <td>Jawaban</td>
                                    <td>:</td>
                                    <td>{{ $analisis_statistik_jawaban['jawaban'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr class="batas">
                    <div class="row mepet">
                        @include('admin.layouts.components.wilayah')
                    </div>
                    <hr class="batas">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable table-hover nowrap">
                                    <thead class="bg-gray disabled color-palette judul-besar">
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Dusun</th>
                                            <th>RW</th>
                                            <th>RT</th>
                                            <th>Umur (Tahun)</th>
                                            <th nowrap>Jenis Kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($main as $data)
                                            <tr>
                                                <td align="center" width="2">{{ $loop->iteration }}</td>
                                                <td><a href="{{ ci_route('penduduk.detail', $data->id_pend) }}" target="_blank">{{ $data->nik }}</a></td>
                                                <td nowrap width="30%"><a href="{{ ci_route('penduduk.detail', $data->id_pend) }}" target="_blank">{{ $data->nama }}</a></td>
                                                <td>{{ strtoupper($data->dusun) }}</td>
                                                <td>{{ $data->rw }}</td>
                                                <td>{{ $data->rt }}</td>
                                                <td>{{ $data->umur }}</td>
                                                <td>{{ App\Enums\JenisKelaminEnum::valueOf($data->sex) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}

            if (filterColumn) {
                if (filterColumn['dusun']) {
                    $('#dusun').val(filterColumn['dusun'])
                    $('#dusun').trigger('change')

                    if (filterColumn['rw']) {
                        $('#rw').val(filterColumn['dusun'] + '__' + filterColumn['rw'])
                        $('#rw').trigger('change')
                    }

                    if (filterColumn['rt']) {
                        $('#rt').find('optgroup[value="' + filterColumn['dusun'] + '__' + filterColumn['rw'] + '"] option').filter(function() {
                            return $(this).val() == filterColumn['rt']
                        }).prop('selected', 1)
                        $('#rt').trigger('change')
                    }
                }
            }
            $('#dusun, #rw, #rt').change(function() {
                const _dusun = $('#dusun').val()
                const _rw = $('#rw').val()
                const _rt = $('#rt').val()
                const _cluster = {}
                if (_dusun) {
                    _cluster['dusun'] = _dusun
                }
                if (_rw) {
                    _cluster['rw'] = _rw.split('__')[1]
                }
                if (_rt) {
                    _cluster['rt'] = _rt
                }
                let query = new URLSearchParams(_cluster);

                let queryString = query.toString();
                const _href = `{{ $form_action }}?${queryString}`
                window.location.href = _href
            })
        });
    </script>
@endpush
