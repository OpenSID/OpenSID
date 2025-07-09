@extends('theme::layouts.right-sidebar')
@include('core::admin.layouts.components.asset_numeral')

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">Data Inventaris {{ ucwords(setting('sebutan_desa')) }}</h2>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="inventaris">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="text-center" rowspan="3" style="vertical-align: middle;">No</th>
                                    <th class="text-center" rowspan="3" style="vertical-align: middle;">Jenis Barang</th>
                                    <th class="text-center" width="340%" rowspan="3" style="vertical-align: middle;">Keterangan</th>
                                    <th class="text-center" colspan="5" style="vertical-align: middle;">Asal barang</th>
                                    <th class="text-center" rowspan="3" style="vertical-align: middle;">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="text-center" rowspan="2">Dibeli Sendiri</th>
                                    <th class="text-center" colspan="3">Bantuan</th>
                                    <th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Pemerintah</th>
                                    <th class="text-center">Provinsi</th>
                                    <th class="text-center">Kabupaten</th>
                                </tr>
                            </thead>
                            <tbody id="inventaris-tbody">

                            </tbody>
                            <tfoot id="inventaris-tfoot">
                                <tr>
                                    <th colspan="3" class="text-center">Total</th>
                                    <th class="pribadi"></th>
                                    <th class="pemerintah"></th>
                                    <th class="provinsi"></th>
                                    <th class="kabupaten"></th>
                                    <th class="sumbangan"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            const _url = `{{ ci_route('internal_api.inventaris') }}`
            const _tbody = document.getElementById('inventaris-tbody')
            const _tfoot = document.getElementById('inventaris-tfoot')
            $.ajax({
                url: _url,
                type: 'GET',
                beforeSend: () => _tbody.innerHTML = `@include('theme::commons.loading')`,
                success: (response) => {
                    let _trString = []
                    let _total = {
                        'pribadi': 0,
                        'pemerintah': 0,
                        'provinsi': 0,
                        'kabupaten': 0,
                        'sumbangan': 0
                    }

                    response.data[0].attributes.forEach((element, key) => {
                        _trString.push(`<tr>
                        <td>${key + 1}</td>
                        <td>${element.jenis}</td>
                        <td>${element.ket}</td>
                        <td>${element.pribadi}</td>
                        <td>${element.pemerintah}</td>
                        <td>${element.provinsi}</td>
                        <td>${element.kabupaten}</td>
                        <td>${element.sumbangan}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="...">
                                <a href="${element.url}" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>`)
                        for (let i in _total) {
                            _total[i] += element[i]
                        }
                    });
                    for (let i in _total) {
                        _tfoot.querySelector(`th.${i}`).innerHTML = _total[i]
                    }
                    _tbody.innerHTML = _trString.join('')

                    setTimeout(() => {
                        $('#inventaris').DataTable({
                            columnDefs: [{
                                targets: [0, 8],
                                orderable: false
                            }],
                            order: [
                                [1, 'asc']
                            ],
                            drawCallback: function(settings) {
                                var api = this.api();
                                api.column(0, {
                                    search: 'applied',
                                    order: 'applied'
                                }).nodes().each(function(cell, i) {
                                    cell.innerHTML = api.page.info().start + i + 1;
                                });
                            }
                        });
                    }, 1000);
                },
                dataType: 'json'
            })
        });
    </script>
@endpush
