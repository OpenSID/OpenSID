@extends('theme::layouts.right-sidebar')
@include('core::admin.layouts.components.asset_numeral')

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">{{ $heading }}</h2>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabelData">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">{{ ucwords(setting('sebutan_dusun')) }}</th>
                            <th class="text-center">RW</th>
                            <th class="text-center">Jiwa</th>
                            <th class="text-center">L</th>
                            <th class="text-center">P</th>
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
        </div>
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

            // Mengelompokkan data
            const groupingData = function(inputData) {
                let groupedData = []
                inputData.forEach(item => {
                    const dusun = item.attributes.dusun;
                    const rw = item.attributes.rw;
                    const sex = item.attributes.sex;
                    const total = item.attributes.total;

                    // Membuat key unik berdasarkan dusun dan rw
                    const key = `${dusun}-${rw}`;

                    // Jika key belum ada, inisialisasi
                    if (!groupedData[key]) {
                        groupedData[key] = {
                            dusun: dusun,
                            rw: rw,
                            totalLaki: 0,
                            totalPerempuan: 0
                        };
                    }

                    // Menjumlahkan total berdasarkan sex
                    if (sex === 1) {
                        groupedData[key].totalLaki += total;
                    } else if (sex === 2) {
                        groupedData[key].totalPerempuan += total;
                    }
                });

                // Mengubah objek menjadi array untuk hasil akhir
                return Object.values(groupedData);
            }


        });
    </script>
@endpush
