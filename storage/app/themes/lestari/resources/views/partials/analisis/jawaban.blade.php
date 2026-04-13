@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')
@include('theme::commons.asset_highcharts')

@section('content')
    <h3 id="indikator"></h3><br>
    <div class="middin-center" style="padding: 5px;">
        <div id="contentpane">
            <div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
            <table class="table table-striped" id="table-jawaban">
                <thead>
                    <tr>
                        <th width="1%">No</th>
                        <th>Jawaban</th>
                        <th width="20%" nowrap>Jumlah Responden</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $.get("{{ route('api.analisis.indikator') . "?filter[id]={$params['filter']['id_indikator']}" }}", function(data) {
            const indikator = data?.data[0]?.attributes?.indikator;

            // Set the text dynamically
            $('#indikator').text(indikator);

            // Initialize the Highcharts with the fetched indikator value
            printChart(indikator);
        });

        var tabelData = $('#table-jawaban').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ordering: false,
            searching: false,
            ajax: {
                url: '{{ route('api.analisis.jawaban') }}',
                method: 'GET',
                data: row => ({
                    ...@json($params),
                    "page[size]": row.length,
                    "page[number]": (row.start / row.length) + 1,
                }),
                dataSrc: json => {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                }
            },
            columnDefs: [{
                targets: '_all',
                className: 'text-nowrap'
            }],
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'attributes.jawaban',
                },
                {
                    data: 'attributes.jml',
                }
            ],
            drawCallback: function(settings) {
                var api = this.api();

                // Update row numbering
                api.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = api.page.info().start + i + 1;
                });

                // Extract data for the chart
                var chartCategories = [];
                var chartData = [];

                api.rows().data().each(function(row) {
                    chartCategories.push(row.attributes.jawaban); // Add the "jawaban" as category
                    chartData.push(row.attributes.jml); // Add the "jml" as data
                });

                // Update the chart with new data
                updateChart(chartCategories, chartData);
            }
        });

        printChart();

        function printChart(indikator) {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'chart',
                    border: 0,
                    defaultSeriesType: 'column'
                },
                title: {
                    text: indikator
                },
                xAxis: {
                    title: {
                        text: ''
                    },
                    categories: []
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
                    data: []
                }]
            });
        }

        function updateChart(categories, data) {
            // Update the categories and data in the chart
            chart.xAxis[0].setCategories(categories);
            chart.series[0].setData(data);
        }
    </script>
@endpush
