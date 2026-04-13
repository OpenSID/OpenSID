@extends('theme::template')
@include('core::admin.layouts.components.asset_numeral')

@section('layout')
    <div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <div class="lg:w-1/3 w-full">
            @include('theme::partials.statistik.sidenav')
        </div>
        <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            <div class="breadcrumb">
                <ol>
                    <li><a href="{{ ci_route('') }}">Beranda</a></li>
                    <li>Data Statistik</li>
                </ol>
            </div>
            <h1 class="text-h2">{{ $heading }}</h1>

            <div class="content py-3 table-responsive">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                            <th>RW</th>
                            <th>Jiwa</th>
                            <th>Lk</th>
                            <th>Pr</th>
                        </tr>
                    </thead>
                    <tbody id="dpt-tbody">

                    </tbody>
                    <tfoot id="dpt-tfoot">
                        <tr class="font-bold">
                            <td colspan="3" class="text-left">TOTAL</td>
                            <td class="total text-right"></td>
                            <td class="total_lk text-right"></td>
                            <td class="total_pr text-right"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <p style="color: red">
                Tanggal Pemilihan : {{ $tanggal_pemilihan }}
            </p>
        </main>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            const _url = `{{ ci_route('internal_api.dpt') }}?tgl_pemilihan={{ $tanggal_pemilihan }}`
            const _tbody = document.getElementById('dpt-tbody')
            const _tfoot = document.getElementById('dpt-tfoot')
            $.ajax({
                url: _url,
                type: 'GET',
                beforeSend: () => _tbody.innerHTML = `@include('theme::commons.loading')`,
                success: (response) => {
                    let _trString = []
                    let _total = {
                        'laki': 0,
                        'perempuan': 0
                    }
                    if (response.data.length) {
                        const groupedData = groupingData(response.data)
                        groupedData.forEach((element, key) => {
                            _trString.push(`<tr>
                            <td class="text-center">${key + 1}</td>
                            <td>${element.dusun}</td>
                            <td class="text-center">${element.rw}</td>
                            <td class="text-right">${element.totalLaki + element.totalPerempuan}</td>
                            <td class="text-right">${element.totalLaki}</td>
                            <td class="text-right">${element.totalPerempuan}</td>
                        </tr>`)
                            _total['laki'] += element.totalLaki
                            _total['perempuan'] += element.totalPerempuan
                        });
                        _tfoot.querySelector(`td.total`).innerHTML = numeral(_total['laki'] + _total['perempuan']).format('0,0')
                        _tfoot.querySelector(`td.total_lk`).innerHTML = numeral(_total['laki']).format('0,0')
                        _tfoot.querySelector(`td.total_pr`).innerHTML = numeral(_total['perempuan']).format('0,0')
                        _tbody.innerHTML = _trString.join('')
                    } else {
                        _tfoot.remove()
                        _tbody.innerHTML = '<tr><td colspan="6">Daftar masih kosong</td></tr>'
                    }
                },
                dataType: 'json'
            })

            const groupingData = function(inputData) {
                let groupedData = []
                inputData.forEach(item => {
                    const dusun = item.attributes.dusun;
                    const rw = item.attributes.rw;
                    const sex = item.attributes.sex;
                    const total = item.attributes.total;

                    const key = `${dusun}-${rw}`;

                    if (!groupedData[key]) {
                        groupedData[key] = {
                            dusun: dusun,
                            rw: rw,
                            totalLaki: 0,
                            totalPerempuan: 0
                        };
                    }

                    if (sex === 1) {
                        groupedData[key].totalLaki += total;
                    } else if (sex === 2) {
                        groupedData[key].totalPerempuan += total;
                    }
                });

                return Object.values(groupedData);
            }
        });
    </script>
@endpush
