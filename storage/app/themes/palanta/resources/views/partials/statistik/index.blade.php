@extends('theme::layouts.right-sidebar')

@section('content')
<div class="heading-module l-flex">
    <div class="heading-module-inner l-flex">
        <i class="fa fa-pie-chart"></i>
        <h1>Data Statistik</h1>
    </div>
</div>

<div class="box-body">
    <div class="c-flex" style="margin:20px 0 20px;text-align:center;width:100%;">
        <h1>{{ $judul }}</h1>
    </div>

    @if (isset($list_tahun))
        <div class="flex justify-between items-center space-x-3 py-2">
            <label for="owner" class="text-xs lg:text-sm">Tahun</label>
            <select class="form-control input-sm" style="margin-bottom: 10px;" id="tahun" name="tahun">
              <option selected="" value="">Semua</option>
              @foreach($list_tahun as $item_tahun)
                  <option @selected($item_tahun == $selected_tahun) value="{{ $item_tahun }}">{{ $item_tahun }}</option>
              @endforeach
          </select>
        </div>
    @endif

    <div class="c-flex" style="margin-bottom: 10px;">
      <a class="btn {{ ($tipe == 1) ? 'btn-primary' : 'btn-primary' }} btn-sm" onclick="switchType();"
        style="margin:0 3px;">Bar Graph</a>
      <a class="btn {{ ($tipe == 0) ? 'btn-success' : 'btn-success' }} btn-sm" onclick="switchType();"
        style="margin:0 3px;">Pie Cart</a>
      <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.cetak") }}?tahun={{ $selected_tahun }}"
        class="btn btn-primary btn-sm" style="margin:0 3px;"
        title="Cetak Laporan" target="_blank">
        <i class="fa fa-print "></i> Cetak
      </a> 
      <a href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.unduh") }}?tahun={{ $selected_tahun }}"
        class="btn btn-success btn-sm " style="margin:0 3px;"
        title="Unduh Laporan" target="_blank">
        <i class="fa fa-print "></i> Unduh
      </a>
    </div>
</div>
<div class="box-body">
    <div id="container"></div>
    <div id="contentpane">
        <div class="ui-layout-north panel top"></div>
    </div>
</div>

<div class="box-body">
    <h3 style="margin:20px 0 0;">Tabel {{ $heading }}</h3>
    <div class="table-responsive">
        <table class="table table-striped" id="table-statistik">
        <thead>
                <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kelompok</th>
                <th colspan="2">Jumlah</th>
                <th colspan="2">Laki-laki</th>
                <th colspan="2">Perempuan</th>
                </tr>
                <tr>
                <th>n</th>
                <th>%</th>
                <th>n</th>
                <th>%</th>
                <th>n</th>
                <th>%</th>
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
        <p style="color: red">
            Diperbarui pada : {{ tgl_indo($last_update) }}
        </p>        
        <div style='float: left;'>
            <button class='btn btn-success btn-sm' id='showData'>Selengkapnya...</button>
        </div>        
        <div style="float: right;">
            <button id='tampilkan' onclick="toggle_tampilkan();" class="btn btn-primary btn-sm">Tampilkan Nol</button>
        </div>
    </div>
</div>

@if (setting('daftar_penerima_bantuan') && $bantuan) 
    <section class="content">
        <div class="row">
            <div class="col-md-12">
              <input id="stat" type="hidden" value="{{$key}}">
                <div class="box box-info">
                    <div class="box-header with-border" style="margin-bottom: 15px;">
                        <h2 class="post_titile">Daftar {{ $heading }}</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="peserta_program">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Program</th>
                                    <th>Nama Peserta</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#tahun').change(function() {
                        const current_url = window.location.href.split('?')[0]
                        window.location.href = `${current_url}?tahun=${$(this).val()}`;
                    })
                    const bantuanUrl = '{{ ci_route('internal_api.peserta_bantuan', $key) }}?filter[tahun]={{ $selected_tahun ?? '' }}'
                    let pesertaDatatable = $('#peserta_program').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: bantuanUrl,
                            type: 'POST',
                            data: function(row) {
                                return {
                                    "page[size]": row.length,
                                    "page[number]": (row.start / row.length) + 1,
                                    "filter[search]": row.search.value,
                                    "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]?.column]?.name,
                                };
                            },
                            dataSrc: function(json) {
                                json.recordsTotal = json.meta.pagination.total
                                json.recordsFiltered = json.meta.pagination.total

                                return json.data
                            },
                        },
                        columns: [{
                                data: null,
                            },
                            {
                                data: 'attributes.nama',
                                name: 'nama'
                            },
                            {
                                data: 'attributes.kartu_nama',
                                name: 'kartu_nama'
                            },
                            {
                                data: 'attributes.kartu_alamat',
                                name: 'kartu_alamat',
                                orderable: false,
                                searchable: false
                            },
                        ],
                        order: [1, 'asc'],
                        language: {
                            url: "".concat(BASE_URL, "/assets/bootstrap/js/dataTables.indonesian.lang")
                        },
                        drawCallback: function drawCallback() {
                            $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
                        }
                    });

                    pesertaDatatable.on('draw.dt', function() {
                        var PageInfo = $('#peserta_program').DataTable().page.info();
                        pesertaDatatable.column(0, {
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1 + PageInfo.start;
                        });
                    });

                });
            </script>
        @endpush
@endif
@endsection

@push('styles')
<style>
    tr.lebih {
        display: none;
    }

    .input-sm {
        padding: 4px 4px;
    }

    @media (max-width:780px) {
        .btn-group-vertical {
            display: block;
        }
    }

    .table-responsive {
        min-height: 275px;
    }
</style>
@endpush

@push('scripts')
        <script type="text/javascript">
            let chart;
            const type = '{{ $default_chart_type ?? 'pie' }}';
            const legend = Boolean({{ (bool) $tipe }});
            let i = 1;
            let status_tampilkan = true;

            function tampilkan_nol(tampilkan = false) {
                if (tampilkan) {
                    $(".nol").parent().show();
                } else {
                    $(".nol").parent().hide();
                }
            }

            function toggle_tampilkan() {
                $('#showData').click();
                tampilkan_nol(status_tampilkan);
                status_tampilkan = !status_tampilkan;
                if (status_tampilkan) $('#tampilkan').text('Tampilkan Nol');
                else $('#tampilkan').text('Sembunyikan Nol');
            }

            function switchType(obj) {
                var chartType = chart_penduduk.series[0].type;
                chart_penduduk.series[0].update({
                    type: (chartType === 'pie') ? 'column' : 'pie'
                });
                $(obj).toggleClass('btn-primary btn-default')
                $(obj).siblings().toggleClass('btn-primary btn-default')
            }

            $(document).ready(function() {
                if ({{ setting('statistik_chart_3d') }}) {
                    chart_penduduk = new Highcharts.Chart({
                        chart: {
                            renderTo: 'container',
                            options3d: {
                                enabled: true,
                                alpha: 45
                            }
                        },
                        title: 0,
                        yAxis: {
                            showEmpty: false,
                        },
                        xAxis: {
                            categories: [],
                        },
                        plotOptions: {
                            series: {
                                colorByPoint: true
                            },
                            column: {
                                pointPadding: -0.1,
                                borderWidth: 0,
                                showInLegend: false,
                                depth: 45
                            },
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                showInLegend: true,
                                depth: 45,
                                innerSize: 70
                            }
                        },
                        legend: {
                            enabled: legend
                        },
                        series: [{
                            type: type,
                            name: 'Jumlah Populasi',
                            shadow: 1,
                            border: 1,
                            data: []
                        }]
                    });
                } else {
                    chart_penduduk = new Highcharts.Chart({
                        chart: {
                            renderTo: 'container'
                        },
                        title: 0,
                        yAxis: {
                            showEmpty: false,
                        },
                        xAxis: {
                            categories: [],
                        },
                        plotOptions: {
                            series: {
                                colorByPoint: true
                            },
                            column: {
                                pointPadding: -0.1,
                                borderWidth: 0,
                                showInLegend: false,
                            },
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                showInLegend: true,
                            }
                        },
                        legend: {
                            enabled: legend
                        },
                        series: [{
                            type: type,
                            name: 'Jumlah Populasi',
                            shadow: 1,
                            border: 1,
                            data: []
                        }]
                    });
                }

                $('#showData').click(function() {
                    $('tr.lebih').show();
                    $('#showData').hide();
                    tampilkan_nol(false);
                });

                let dataStats = [];

                $.ajax({
                    url: `{{ ci_route('internal_api.statistik', $key) }}?tahun={{ $selected_tahun ?? '' }}`,
                    method: 'get',
                    data: {},
                    beforeSend: function() {
                        $('#showData').hide()
                    },
                    success: function(json) {
                        dataStats = json.data.map(item => {
                            const {
                                id
                            } = item;
                            const {
                                nama,
                                jumlah,
                                laki,
                                perempuan,
                                persen,
                                persen1,
                                persen2
                            } = item.attributes;
                            return {
                                id,
                                nama,
                                jumlah,
                                persen,
                                laki,
                                persen1,
                                perempuan,
                                persen2
                            };
                        });

                        const table = document.getElementById('table-statistik')
                        const tbody = table.querySelector('tbody')
                        let _showBtnSelengkapnya = false
                        let categories = [],
                            data = []
                        // Populate table rows
                        dataStats.forEach((item, index) => {
                            const row = document.createElement('tr');
                            if (index > 11 && !['666', '777', '888'].includes(item['id'])) {
                                row.className = 'lebih';
                                _showBtnSelengkapnya = true
                            }
                            for (let key in item) {
                                const cell = document.createElement('td');
                                let text = item[key]
                                let className = 'angka'
                                if (key == 'id') {
                                    className = ''
                                    text = index + 1
                                    if (['666', '777', '888'].includes(item[key])) {
                                        text = ''
                                    }
                                }
                                if (key == 'nama') {
                                    className = ''
                                }
                                if (key == 'jumlah' && item[key] <= 0) {
                                    if (!['666', '777', '888'].includes(item['id'])) {
                                        className += ' nol'
                                    }

                                }
                                cell.className = className
                                cell.textContent = text;
                                row.appendChild(cell);
                            }

                            tbody.appendChild(row);
                        });

                        tampilkan_nol(false);

                        if (_showBtnSelengkapnya) {
                            $('#showData').show()
                        }

                        for (const stat of dataStats) {
                            if (stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA') {
                                let filteredData = [stat.nama, parseInt(stat.jumlah)];
                                categories.push(i);
                                data.push(filteredData);
                                i++;
                            }
                        }

                        chart_penduduk.xAxis[0].update({
                            categories: categories
                        });

                        chart_penduduk.series[0].setData(data);
                    },
                })
            });
        </script>
    @endpush