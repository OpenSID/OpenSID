@include('admin.layouts.components.highchartjs')

@extends('admin.layouts.index')

@section('title')
    <h1>Laporan Perkembangan</h1>
@endsection

@section('breadcrumb')
    <li class="active">Laporan Perkembangan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="laporan-filter-heading">
                        <i class="fa fa-filter"></i>
                        <span>Filter Laporan</span>
                    </div>
                    <form id="filter-perkembangan" method="get" action="{{ route('laporan-perkembangan.index') }}" class="form-inline laporan-filter">
                        <div class="form-group">
                            <label for="jenis">Jenis</label>
                            <select name="jenis" class="form-control input-sm select2">
                                <option value="penduduk" @selected($jenis == 'penduduk')>Penduduk</option>
                                <option value="keluarga" @selected($jenis == 'keluarga')>Keluarga</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="periode">Periode</label>
                            <select name="periode" class="form-control input-sm select2">
                                <option value="bulan" @selected($periode == 'bulan')>Bulanan</option>
                                <option value="tahun" @selected($periode == 'tahun')>Tahunan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipe_grafik">Grafik</label>
                            <select name="tipe_grafik" class="form-control input-sm select2">
                                <option value="line">Garis</option>
                                <option value="column">Batang</option>
                                <option value="area">Area</option>
                                <option value="pie">Pie</option>
                            </select>
                        </div>
                        <div class="form-group filter-bulanan">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" class="form-control input-sm select2">
                                @foreach (tahun(awal: 2016) as $item)
                                    <option value="{{ $item }}" @selected($tahun == $item)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group filter-bulanan">
                            <label for="bulan_awal">Bulan Awal</label>
                            <select name="bulan_awal" class="form-control input-sm select2">
                                @foreach (bulan() as $no_bulan => $nama_bulan)
                                    <option value="{{ $no_bulan }}" @selected($bulanAwal == $no_bulan)>{{ $nama_bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group filter-bulanan">
                            <label for="bulan_akhir">Bulan Akhir</label>
                            <select name="bulan_akhir" class="form-control input-sm select2">
                                @foreach (bulan() as $no_bulan => $nama_bulan)
                                    <option value="{{ $no_bulan }}" @selected($bulanAkhir == $no_bulan)>{{ $nama_bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group filter-tahunan">
                            <label for="tahun_awal">Tahun Awal</label>
                            <select name="tahun_awal" class="form-control input-sm select2">
                                @foreach (tahun(awal: 2016) as $item)
                                    <option value="{{ $item }}" @selected($tahunAwal == $item)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group filter-tahunan">
                            <label for="tahun_akhir">Tahun Akhir</label>
                            <select name="tahun_akhir" class="form-control input-sm select2">
                                @foreach (tahun(awal: 2016) as $item)
                                    <option value="{{ $item }}" @selected($tahunAkhir == $item)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="box-body perkembangan-body">
                    <div id="loading-perkembangan" class="perkembangan-loading" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i> Memuat data...
                    </div>
                    <div class="chart-panel">
                        <div id="chart-perkembangan"></div>
                    </div>
                    <hr class="batas">
                    <div class="table-heading">
                        <i class="fa fa-table"></i>
                        <span>Rincian Data</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover tabel-daftar">
                            <thead class="bg-gray color-palette">
                                <tr>
                                    <th>No</th>
                                    <th id="periode-label">Periode ({{ $periode == 'tahun' ? 'Tahun' : 'Bulan' }})</th>
                                    @foreach ($kolom as $key => $label)
                                        <th class="padat text-center kolom-statistik kolom-{{ $key }}">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="tabel-perkembangan-body">
                                @foreach ($main as $item)
                                    <tr>
                                        <td class="padat">{{ $loop->iteration }}</td>
                                        <td>{{ $item['label'] }}</td>
                                        @foreach ($kolom as $key => $label)
                                            <td class="padat text-center kolom-statistik kolom-{{ $key }}">{{ number_format($item[$key], 0, ',', '.') }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function toggleFilterPeriode() {
                const tahunan = $('select[name="periode"]').val() === 'tahun';

                $('.filter-tahunan').toggle(tahunan);
                $('.filter-bulanan').toggle(!tahunan);
                $('#periode-label').text('Periode (' + (tahunan ? 'Tahun' : 'Bulan') + ')');
            }

            function tipeGrafik() {
                return $('select[name="tipe_grafik"]').val() || 'line';
            }

            function normalizeRange(changedName) {
                const tahunAwal = $('select[name="tahun_awal"]');
                const tahunAkhir = $('select[name="tahun_akhir"]');
                const bulanAwal = $('select[name="bulan_awal"]');
                const bulanAkhir = $('select[name="bulan_akhir"]');

                if (parseInt(tahunAwal.val()) > parseInt(tahunAkhir.val())) {
                    if (changedName === 'tahun_akhir') {
                        tahunAwal.val(tahunAkhir.val()).trigger('change.select2');
                    } else {
                        tahunAkhir.val(tahunAwal.val()).trigger('change.select2');
                    }
                }

                if (parseInt(bulanAwal.val()) > parseInt(bulanAkhir.val())) {
                    if (changedName === 'bulan_akhir') {
                        bulanAwal.val(bulanAkhir.val()).trigger('change.select2');
                    } else {
                        bulanAkhir.val(bulanAwal.val()).trigger('change.select2');
                    }
                }
            }

            toggleFilterPeriode();
            $('#filter-perkembangan select').change(function() {
                normalizeRange($(this).attr('name'));
                toggleFilterPeriode();

                if ($(this).attr('name') === 'tipe_grafik') {
                    renderChart(baseSeries, labels, currentVisibility());

                    return;
                }

                loadData();
            });

            const kolom = @json($kolom);
            let labels = @json(array_column($main, 'label'));
            let baseSeries = @json($series);
            const chart = Highcharts.chart('chart-perkembangan', {
                chart: {
                    type: tipeGrafik()
                },
                title: {
                    text: 'Statistik Perkembangan {{ ucfirst($jenis) }}'
                },
                xAxis: {
                    categories: @json(array_column($main, 'label'))
                },
                yAxis: {
                    title: {
                        text: 'Jumlah {{ ucfirst($jenis) }}'
                    }
                },
                tooltip: {
                    shared: false,
                    borderRadius: 4,
                    padding: 10,
                    shadow: true,
                    style: {
                        fontSize: '13px',
                        lineHeight: '18px'
                    },
                    formatter: function() {
                        if (this.series.type === 'pie') {
                            return this.series.name + '<br>' + this.point.name + ' : ' + formatNumber(this.y);
                        }

                        const label = this.point.category || this.series.xAxis.categories[this.point.x] || this.x;
                        const rows = this.series.chart.series
                            .filter(series => series.visible)
                            .map(series => ({
                                series: series,
                                point: series.points.find(point => point.x === this.point.x),
                            }))
                            .filter(item => item.point && Math.abs(item.point.plotY - this.point.plotY) <= 8);

                        return label + '<br>' + rows.map(item => {
                            return item.series.name + ' : ' + formatNumber(item.point.y);
                        }).join('<br>');
                    }
                },
                plotOptions: {
                    pie: {
                        showInLegend: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y:,.0f}'
                        },
                        point: {
                            events: {
                                legendItemClick: function() {
                                    $('.kolom-' + this.id).toggle(!this.visible);
                                }
                            }
                        }
                    },
                    series: {
                        events: {
                            hide: function() {
                                $('.kolom-' + this.options.id).hide();
                            },
                            show: function() {
                                $('.kolom-' + this.options.id).show();
                            }
                        }
                    }
                },
                series: chartSeries(baseSeries, labels, {})
            });

            let request = null;
            let requestId = 0;

            function setLoading(active) {
                $('#loading-perkembangan').toggle(active);
                $('#filter-perkembangan select').prop('disabled', active);
            }

            function formatNumber(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            }

            function renderTable(rows) {
                const tbody = $('#tabel-perkembangan-body');
                tbody.empty();

                rows.forEach(function(item, index) {
                    const tr = $('<tr>');
                    tr.append($('<td>').addClass('padat').text(index + 1));
                    tr.append($('<td>').text(item.label));

                    Object.keys(kolom).forEach(function(key) {
                        tr.append($('<td>').addClass('padat text-center kolom-statistik kolom-' + key).text(formatNumber(item[key])));
                    });

                    tbody.append(tr);
                });

                applyVisibleColumns();
            }

            function applyVisibleColumns() {
                if (tipeGrafik() === 'pie' && chart.series[0]) {
                    chart.series[0].points.forEach(function(point) {
                        $('.kolom-' + point.id).toggle(point.visible);
                    });
                } else {
                    chart.series.forEach(function(series) {
                        $('.kolom-' + series.options.id).toggle(series.visible);
                    });
                }
            }

            function currentVisibility() {
                const visibility = {};

                if (tipeGrafik() === 'pie' && chart.series[0]) {
                    chart.series[0].points.forEach(function(point) {
                        visibility[point.id] = point.visible;
                    });
                } else {
                    chart.series.forEach(function(series) {
                        visibility[series.options.id] = series.visible;
                    });
                }

                return visibility;
            }

            applyVisibleColumns();

            function chartSeries(series, categories, visibility) {
                if (tipeGrafik() !== 'pie') {
                    return series.map(function(item) {
                        item.visible = visibility[item.id] ?? item.visible ?? true;

                        return item;
                    });
                }

                const index = Math.max(categories.length - 1, 0);

                return [{
                    type: 'pie',
                    name: categories[index] || '',
                    data: series.map(function(item) {
                        return {
                            id: item.id,
                            name: item.name,
                            y: item.data[index] || 0,
                            visible: visibility[item.id] ?? item.visible ?? true
                        };
                    })
                }];
            }

            function renderChart(series, categories, visibility) {
                chart.update({
                    chart: {
                        type: tipeGrafik()
                    },
                    xAxis: {
                        categories: categories
                    }
                }, false);

                while (chart.series.length) {
                    chart.series[0].remove(false);
                }

                chartSeries(series, categories, visibility).forEach(function(item) {
                    chart.addSeries(item, false);
                });

                chart.redraw();
                applyVisibleColumns();
            }

            function loadData() {
                if (request) {
                    request.abort();
                }

                const activeRequestId = ++requestId;
                const form = $('#filter-perkembangan');
                const params = form.serialize();

                setLoading(true);

                request = $.getJSON('{{ route('laporan-perkembangan.data') }}', params)
                    .done(function(response) {
                        const visibility = currentVisibility();
                        baseSeries = response.series;
                        labels = response.labels;

                        chart.setTitle({
                            text: response.judul
                        });
                        chart.yAxis[0].setTitle({
                            text: response.sumbuY
                        });
                        renderChart(baseSeries, labels, visibility);

                        renderTable(response.main);
                    })
                    .always(function() {
                        if (activeRequestId === requestId) {
                            setLoading(false);
                        }
                    });
            }
        });
    </script>
@endpush

@push('css')
    <style>
        .perkembangan-loading {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #d2d6de;
            border-radius: 3px;
            color: #444;
            font-weight: 600;
            left: 50%;
            padding: 8px 14px;
            position: absolute;
            top: 170px;
            transform: translateX(-50%);
            z-index: 10;
        }

        .perkembangan-body {
            position: relative;
        }

        .laporan-filter-heading,
        .table-heading {
            color: #111;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .laporan-filter-heading .fa,
        .table-heading .fa {
            margin-right: 6px;
        }

        .laporan-filter {
            align-items: flex-end;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 14px;
        }

        .laporan-filter .form-group {
            margin-bottom: 0;
        }

        .laporan-filter label {
            color: #3f4a54;
            display: block;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .laporan-filter .form-control {
            min-width: 165px;
        }

        .select2-container--open .select2-dropdown {
            min-width: 165px;
        }

        .chart-panel {
            background: #fff;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }

        .tabel-daftar .kolom-statistik {
            min-width: 86px;
            width: 86px;
        }

        .tabel-daftar thead th {
            padding-bottom: 12px;
            padding-top: 12px;
            vertical-align: middle;
        }

        .tabel-daftar thead th {
            background: #d8dde5;
            color: #111;
        }

        .tabel-daftar tbody td {
            padding-bottom: 10px;
            padding-top: 10px;
            vertical-align: middle;
        }
    </style>
@endpush
