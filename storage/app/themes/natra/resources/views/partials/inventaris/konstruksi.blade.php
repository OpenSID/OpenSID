@extends('theme::layouts.right-sidebar')
@include('core::admin.layouts.components.asset_numeral')

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">Data {{ $judul }}</h2>
        <div class="box-body">
            <div class="table-responsive">
                <table id="inventaris" class="table table-bordered table-hover">
                    <thead class="bg-gray">
                        <tr>
                            <th class="text-center" rowspan="2">No</th>
                            <th class="text-center" rowspan="2">Nama Barang</th>
                            <th class="text-center" rowspan="2">Fisik Bangunan (P, SP, D)</th>
                            <th class="text-center" rowspan="2">Luas (M<sup>2</sup>)</th>
                            <th class="text-center" colspan="2">Dokumen</th>
                            <th class="text-center" rowspan="2">Tgl,bln,thn Mulai</th>
                            <th class="text-center" rowspan="2">Status Tanah</th>
                            <th class="text-center" rowspan="2">Asal Usul Biaya</th>
                            <th class="text-center" rowspan="2">Nilai Kontrak (Rp)</th>
                        </tr>
                        <tr>
                            <th class="text-center" rowspan="1">Tanggal</th>
                            <th class="text-center" rowspan="1">Nomor</th>
                        </tr>
                    </thead>
                    <tbody id="inventaris-tbody">

                    </tbody>

                    <tfoot id="inventaris-tfoot">
                        <tr>
                            <th colspan="9" class="text-right">Total:</th>
                            <th class="total text-right"></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            const _url = `{{ ci_route('internal_api.inventaris-kontruksi') }}`
            const _tbody = document.getElementById('inventaris-tbody')
            const _tfoot = document.getElementById('inventaris-tfoot')
            $.ajax({
                url: _url,
                type: 'GET',
                beforeSend: () => _tbody.innerHTML = `@include('theme::commons.loading')`,
                success: (response) => {
                    let _trString = []
                    let _total = 0
                    if (response.data.length) {
                        response.data.forEach((element, key) => {
                            _trString.push(`<tr>
                            <td>${key + 1}</td>
                            <td>${element.attributes.nama_barang}</td>
                            <td>${element.attributes.kondisi_bangunan}</td>
                            <td>${element.attributes.luas_bangunan}</td>
                            <td>${element.attributes.tanggal_dokument}</td>
                            <td>${element.attributes.no_dokument}</td>
                            <td>${element.attributes.tanggal}</td>
                            <td>${element.attributes.status_tanah}</td>
                            <td>${element.attributes.asal}</td>
                            <td>${element.attributes.harga_format}</td>
                        </tr>`)
                            _total += element.attributes.harga
                        });
                        _tfoot.querySelector(`th.total`).innerHTML = numeral(_total).format()
                        _tbody.innerHTML = _trString.join('')
                    } else {
                        _tfoot.remove()
                        _tbody.innerHTML = ''
                    }
                    setTimeout(() => {
                        $('#inventaris').DataTable({
                            columnDefs: [{
                                targets: [0],
                                orderable: false
                            }],
                            order: [
                                [1, 'asc']
                            ]
                        });
                    }, 1000);
                },
                dataType: 'json'
            })
        });
    </script>
@endpush
