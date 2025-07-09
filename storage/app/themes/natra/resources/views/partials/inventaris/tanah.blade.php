@extends('theme::layouts.right-sidebar')
@include('core::admin.layouts.components.asset_numeral')

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">Data {{ $judul }}</h2>
        <div class="box-body">
            <div class="table-responsive">
                <table id="inventaris" class="table table-bordered dataTable table-hover">
                    <thead class="bg-gray">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Kode Barang / Nomor Registrasi</th>
                            <th class="text-center">Luas (M<sup>2</sup>)</th>
                            <th class="text-center">Tahun Pengadaan</th>
                            <th class="text-center">Letak/Alamat</th>
                            <th class="text-center">Nomor Sertifikat</th>
                            <th class="text-center">Asal Usul</th>
                            <th class="text-center">Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody id="inventaris-tbody">

                    </tbody>
                    <tfoot id="inventaris-tfoot">
                        <tr>
                            <th colspan="8" class="text-right">Total:</th>
                            <th class="text-right total"></th>
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
            const _url = `{{ ci_route('internal_api.inventaris-tanah') }}`
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
                            <td>${element.attributes.kode_barang}<br>${element.attributes.register}</td>
                            <td>${element.attributes.luas}</td>
                            <td>${element.attributes.tahun_pengadaan}</td>
                            <td>${element.attributes.letak}</td>
                            <td>${element.attributes.no_sertifikat}</td>
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
