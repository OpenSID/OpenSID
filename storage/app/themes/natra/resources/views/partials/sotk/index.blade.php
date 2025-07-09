@extends('theme::layouts.full-content')
@include('theme::commons.asset_highcharts')

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">Struktur Organisasi dan Tata Kerja {{ setting('sebutan_pemerintah_desa') }}
        </h2>
        <div class="box-body">
            <center>
                <figure class="highcharts-figure" style="max-width: 100%;">
                    <div id="container-sotk"></div>
                </figure>
            </center>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadHighcharts(strukturPemerintah, strukturSotk) {
                Highcharts.chart('container-sotk', {
                    chart: {
                        height: 600,
                        inverted: true
                    },

                    title: {
                        text: ('Struktur Organisasi Pemerintah ' + setting.sebutan_desa).replace(/\b\w/g, char => char.toUpperCase())
                    },

                    accessibility: {
                        point: {
                            descriptionFormatter: function(point) {
                                var nodeName = point.toNode.name,
                                    nodeId = point.toNode.id,
                                    nodeDesc = nodeName === nodeId ? nodeName : nodeName + ', ' + nodeId,
                                    parentDesc = point.fromNode.id;
                                return point.index + '. ' + nodeDesc + ', reports to ' + parentDesc + '.';
                            }
                        }
                    },

                    series: [{
                        type: 'organization',
                        name: setting.sebutan_desa + ' ' + config.nama_desa,
                        keys: ['from', 'to'],
                        data: strukturSotk,
                        levels: [{
                            level: 0,
                            color: 'gold',
                            dataLabels: {
                                color: 'black'
                            },
                            height: 25
                        }, {
                            level: 1,
                            color: 'MediumTurquoise',
                            dataLabels: {
                                color: 'white'
                            },
                            height: 25
                        }, {
                            level: 2,
                            color: '#980104',
                            dataLabels: {
                                color: 'white'
                            },
                            height: 25
                        }, {
                            level: 4,
                            color: '#359154',
                            dataLabels: {
                                color: 'white'
                            },
                            height: 25
                        }],

                        linkColor: "#ccc",
                        linkLineWidth: 2,
                        linkRadius: 0,
                        nodes: strukturPemerintah,
                        colorByPoint: false,
                        color: '#007ad0',
                        dataLabels: {
                            color: 'white'
                        },
                        shadow: {
                            color: '#ccc',
                            width: 10,
                            offsetX: 0,
                            offsetY: 0
                        },
                        borderColor: 'white',
                        nodeWidth: 75
                    }],
                    tooltip: {
                        outside: true
                    },
                    exporting: {
                        allowHTML: true,
                        sourceWidth: 800,
                        sourceHeight: 600
                    }

                });
            }

            var strukturPemerintah = [];
            var strukturSotk = [];

            function loadSotk() {
                const apiPemerintah = '{{ route('api.pemerintah') }}';
                const $sotkList = $('#sotk-list');
                $sotkList.html('<p class="text-center">Memuat...</p>');

                $.get(apiPemerintah, function(response) {
                    const pemerintah = response.data;

                    if (!pemerintah.length) {
                        $sotkList.html('<p class="py-2 text-center">Tidak ada SOTK yang tersedia</p>');
                        return;
                    }

                    const initialStructure = [{
                            id: 'BPD',
                            color: 'gold',
                            column: 0,
                            offset: '-150'
                        },
                        {
                            id: 'LPM',
                            color: 'gold',
                            column: 0,
                            dataLabels: {
                                color: 'black'
                            },
                            offset: '150'
                        }
                    ];

                    strukturPemerintah.push(...initialStructure);
                    strukturSotk.push(['BPD', 'LPM']);

                    pemerintah.forEach(item => {
                        const data = {
                            id: parseInt(item.id),
                            title: item.attributes.nama_jabatan,
                            name: item.attributes.nama,
                            image: item.attributes.foto,
                            column: item.attributes.bagan_tingkat || undefined,
                            offset: item.attributes.bagan_offset || undefined,
                            layout: item.attributes.bagan_layout || undefined,
                            color: item.attributes.bagan_warna || undefined,
                        };

                        strukturPemerintah.push(data);

                        if (item.attributes.atasan) {
                            strukturSotk.push([parseInt(item.attributes.atasan), data.id]);
                        }
                    });

                    $sotkList.html(`
                    <center>
                        <figure class="highcharts-figure" style="max-width: 100%;">
                            <div id="container-sotk" style="max-width: 100%;"></div>
                        </figure>
                    </center>
                `);

                    loadHighcharts(strukturPemerintah, strukturSotk);
                });
            }

            loadSotk();
        });
    </script>
@endpush
