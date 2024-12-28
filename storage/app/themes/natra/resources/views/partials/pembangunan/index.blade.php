@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    <div class="single_category wow fadeInDown">
        <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Pembangunan</span></h2>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <div class="row" id="pembangunan-list">
            </div>

            @include('theme::commons.pagination')
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadPembangunan(params = {}) {

                var apiPembangunan = '{{ route('api.pembangunan') }}';

                $('#pagination-container').hide();

                $.get(apiPembangunan, params, function(data) {
                    var pembangunan = data.data;
                    var pembangunanList = $('#pembangunan-list');

                    pembangunanList.empty();

                    if (!pembangunan.length) {
                        pembangunanList.html('<p class="text-center">Tidak ada pembangunan yang ditemukan.</p>');
                        return;
                    }

                    pembangunan.forEach(function(item) {
                        var url = SITE_URL + 'pembangunan/' + item.attributes.slug;
                        var fotoHTML = `<img width="auto" class="img-fluid img-thumbnail card-img-top" src="${item.attributes.foto}" alt="Foto Pembangunan"/>`;

                        var pembangunanHTML = `
                        <div class="col-sm-4">
                            <div class="card">
                                ${fotoHTML}
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th width="auto">Nama Kegiatan</th>
                                                <td width="1%">:</td>
                                                <td>${item.attributes.judul}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>:</td>
                                                <td>${item.attributes.lokasi}</td>
                                            </tr>
                                            <tr>
                                                <th>Tahun</th>
                                                <td>:</td>
                                                <td>${item.attributes.tahun_anggaran}</td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td>:</td>
                                                <td>${item.attributes.keterangan.length > 100 ? item.attributes.keterangan.substring(0, 100) + '...' : item.attributes.keterangan}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <a href="${url}" class="btn btn-primary">Selengkapnya</a>
                            </div>
                        </div>
                    `;

                        pembangunanList.append(pembangunanHTML);
                    });

                    initPagination(data);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');

                params['page[number]'] = page;

                loadPembangunan(params);
            });

            loadPembangunan();
        });
    </script>
@endpush
