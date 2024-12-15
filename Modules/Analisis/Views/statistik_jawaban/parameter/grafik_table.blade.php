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
                <div class="box-body">
                    <div class="row mepet">
                        @include('admin.layouts.components.wilayah')
                        <div class="col-sm-2">
                            <a href="{{ ci_route('analisis_statistik_jawaban', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Laporan Per Indikator"><i
                                    class="fa fa-arrow-circle-left "
                                ></i>Kembali Ke Laporan
                                Per Indikator</a>
                        </div>
                    </div>
                    <hr class="batas">
                    <div class="col-sm-12">
                        <h5 class="box-title"><b>{{ $analisis_statistik_jawaban['pertanyaan'] }}</b></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jawaban</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($main as $data)
                                        <tr>
                                            <td align="center" width="2">{{ $loop->iteration }}</td>
                                            <td>{{ $data['jawaban'] }}</td>
                                            <td>{{ $data['nilai'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Pengaturan Grafik (Graph) Data Statistik-->
    <script type="text/javascript">
        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}
            hiRes();

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
        var chart;

        function hiRes() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'chart',
                    border: 0,
                    defaultSeriesType: 'column'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    title: {
                        text: ''
                    },
                    categories: [
                        @foreach ($main as $data)
                            @if ($data['nilai'] != '-')
                                {!! "'{$data['jawaban']}'," !!}
                            @endif
                        @endforeach
                    ]
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Populasi'
                    }
                },
                legend: {
                    layout: 'vertical',
                    enabled: false
                },
                plotOptions: {
                    series: {
                        colorByPoint: true
                    },
                    column: {
                        pointPadding: 0,
                        borderWidth: 0
                    }
                },
                series: [{
                    shadow: 1,
                    border: 0,
                    data: [
                        @foreach ($main as $data)
                            @if ($data['jawaban'] != 'TOTAL')
                                @if ($data['nilai'] != '-')
                                    {{ $data['nilai'] }},
                                @endif
                            @endif
                        @endforeach
                    ]
                }]
            });
        };
    </script>
    <!-- Highcharts -->
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-more.js') }}"></script>
@endpush
