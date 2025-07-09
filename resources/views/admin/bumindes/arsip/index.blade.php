@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')

@section('title')
    <h1>
        Arsip {{ ucwords(setting('sebutan_desa')) }} | {{ ${$ci->input->get('kategori')}['title'] ?? 'Layanan Surat' }}
    </h1>
@endsection

@section('breadcrumb')
    <li class='active'>Arsip {{ ucwords(setting('sebutan_desa')) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-lg-4 col-xs-4">
            <div class="small-box rounded bg-yellow">
                <div class="inner">
                    <h3>{{ $dokumen_desa['total'] }}</h3>
                    <p>{{ $dokumen_desa['title'] }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-document"></i>
                </div>
                <a href="{{ site_url("{$ci->controller}?kategori={$dokumen_desa['uri']}") }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
            <div class="small-box rounded bg-aqua">
                <div class="inner">
                    <h3>{{ $surat_masuk['total'] }}</h3>
                    <p>{{ $surat_masuk['title'] }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-email"></i>
                </div>
                <a href="{{ site_url("{$ci->controller}?kategori={$surat_masuk['uri']}") }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
            <div class="small-box rounded bg-blue">
                <div class="inner">
                    <h3>{{ $surat_keluar['total'] }}</h3>
                    <p>{{ $surat_keluar['title'] }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-email"></i>
                </div>
                <a href="{{ site_url("{$ci->controller}?kategori={$surat_keluar['uri']}") }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
            <div class="small-box rounded bg-purple">
                <div class="inner">
                    <h3>{{ $kependudukan['total'] }}</h3>
                    <p>{{ $kependudukan['title'] }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="{{ site_url("{$ci->controller}?kategori={$kependudukan['uri']}") }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
            <div class="small-box rounded bg-green">
                <div class="inner">
                    <h3>{{ $layanan_surat['total'] }}</h3>
                    <p>{{ $layanan_surat['title'] }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-document-text"></i>
                </div>
                <a href="{{ site_url("{$ci->controller}?kategori={$layanan_surat['uri']}") }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-body with-border">
            <div class="row mepet">
                <div class="col-sm-4">
                    <select class="form-control input-sm select2" name="jenis">
                        <option value="0">Pilih Jenis Dokumen</option>
                        @foreach ($list_jenis as $key => $jenis)
                            <option value="{{ $key }}">{{ strtoupper(str_replace('_', ' ', $jenis)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="tahun">
                        <option value="0">Pilih Tahun</option>
                        @foreach ($list_tahun as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="tabeldata" class="table table-bordered table-striped table-hover">
                            <thead class="bg-gray color-palette">
                                <tr>
                                    <th class="padat">NO</th>
                                    <th class="padat">AKSI</th>
                                    <th>NOMOR DOKUMEN</th>
                                    <th>TANGGAL DOKUMEN</th>
                                    <th>NAMA DOKUMEN</th>
                                    <th>JENIS DOKUMEN</th>
                                    <th>LOKASI ARSIP</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            let kategori = urlParams.get('kategori');

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('bumindes_arsip') }}",
                    data: function(req) {
                        req.jenis = $('[name="jenis"').val();
                        req.tahun = $('[name="tahun"').val();
                        req.kategori = kategori ?? 'layanan_surat';
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nomor_dokumen',
                        name: 'nomor_dokumen',
                        class: 'aksi',
                    },
                    {
                        data: 'tanggal_dokumen',
                        name: 'tanggal_dokumen',
                        class: 'aksi',
                    },
                    {
                        data: 'nama_dokumen',
                        name: 'nama_dokumen',
                    },
                    {
                        data: 'nama_jenis',
                        name: 'nama_jenis',
                        class: 'aksi',
                    },
                    {
                        data: 'lokasi_arsip',
                        name: 'lokasi_arsip',
                    },
                ],
                order: [
                    [3, 'desc']
                ],
            });

            $('[name="jenis"').change(function() {
                TableData.draw()
            })

            $('[name="tahun"').change(function() {
                TableData.draw()
            })

            if (ubah == 0) {
                TableData.column(1).visible(false)
            }
        })
    </script>
@endpush
