<div class="box box-info">
    <div class="box-header with-border">
        @if (can('u'))
            <a href="{{ ci_route('pengurus.form') }}" id="btn-add" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            <div class="btn-group btn-group-vertical">
                <a class="btn btn-social btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Aksi Data Terpilih</a>
                <ul class="dropdown-menu" role="menu">
                    @if (can('u'))
                        <li>
                            <a
                                href="{{ ci_route('pengurus/atur_bagan') }}"
                                title="Ubah Data"
                                data-remote="false"
                                data-toggle="modal"
                                data-target="#modalBox"
                                data-title="Atur Struktur Bagan"
                                class="btn btn-social btn-block btn-sm aksi-terpilih"
                            ><i class="fa fa-sitemap"></i> Atur Struktur Bagan</a>
                        </li>
                    @endif
                    @if (can('h'))
                        <li>
                            <a href="#confirm-delete" class="btn btn-social btn-block btn-sm hapus-terpilih" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('pengurus.delete') }}')"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        <div class="btn-group btn-group-vertical">
            <a class="btn btn-social bg-purple btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi Lainnya</a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a
                        href="{{ ci_route('pengurus/dialog/cetak') }}"
                        class="btn btn-social btn-block btn-sm"
                        title="Cetak Buku Pemerintah Desa"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Buku Pemerintah Desa"
                    ><i class="fa fa-print "></i> Cetak</a>
                </li>
                <li>
                    <a
                        href="{{ ci_route('pengurus/dialog/unduh') }}"
                        class="btn btn-social btn-block btn-sm"
                        title="Unduh Buku Pemerintah Desa"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Unduh Buku Pemerintah Desa"
                    ><i class="fa fa-download"></i> Unduh</a>
                </li>
            </ul>
        </div>
        <div class="btn-group btn-group-vertical">
            <a class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Bagan Organisasi</a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="{{ ci_route('pengurus/bagan') }}" title="Bagan Tanpa BPD" class="btn btn-social btn-block btn-sm"><i class="fa fa-sitemap"></i> Bagan Tanpa BPD</a>
                </li>
                <li>
                    <a href="{{ ci_route('pengurus/bagan/bpd') }}" title="Bagan Dengan BPD" class="btn btn-social btn-block btn-sm"><i class="fa fa-sitemap"></i> Bagan Dengan BPD</a>
                </li>
            </ul>
        </div>
        <a href="{{ ci_route('pengurus.jabatan') }}" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Jabatan">
            <i class="fa fa fa-list"></i>Jabatan
        </a>
        @if (can('b', 'jam-kerja') || can('b', 'hari-libur') || can('b', 'rekapitulasi') || can('b', 'kehadiran-pengaduan'))
            <div class="btn-group btn-group-vertical">
                <a class="btn btn-social bg-orange btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Kehadiran</a>
                <ul class="dropdown-menu" role="menu">
                    @if (can('b', 'jam-kerja'))
                        <li>
                            <a href="{{ ci_route('kehadiran_jam_kerja') }}" title="Jam Kerja" class="btn btn-social btn-block btn-sm"><i class="fa fa-clock-o"></i> Jam Kerja</a>
                        </li>
                    @endif
                    @if (can('b', 'hari-libur'))
                        <li>
                            <a href="{{ ci_route('kehadiran_hari_libur') }}" title="Hari Libur" class="btn btn-social btn-block btn-sm"><i class="fa fa-calendar"></i> Hari Libur</a>
                        </li>
                    @endif
                    @if (can('b', 'rekapitulasi'))
                        <li>
                            <a href="{{ ci_route('kehadiran_rekapitulasi') }}" title="Kehadiran" class="btn btn-social btn-block btn-sm"><i class="fa fa-list"></i> Kehadiran</a>
                        </li>
                    @endif
                    @if (can('b', 'kehadiran-pengaduan'))
                        <li>
                            <a href="{{ ci_route('kehadiran_pengaduan') }}" title="Pengaduan" class="btn btn-social btn-block btn-sm"><i class="fa fa-exclamation"></i> Pengaduan</a>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
    <div class="box-body">
        <div class="row mepet">
            <div class="col-sm-2">
                <select id="status" class="form-control input-sm select2">
                    <option value="">Pilih Status</option>
                    @foreach ($status as $key => $item)
                        <option value="{{ $key }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select id="kehadiran" class="form-control input-sm select2">
                    <option value="">Pilih Status Kehadiran</option>
                    <option value="1">Kehadiran Perangkat Aktif</option>
                    <option value="0">Kehadiran Perangkat Tidak Aktif</option>
                </select>
            </div>
        </div>
        <hr class="batas">
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tabeldata">
                <thead>
                    <tr>
                        <th class="padat">#</th>
                        <th><input type="checkbox" id="checkall" /></th>
                        <th class="padat">NO</th>
                        <th class="padat">AKSI</th>
                        <th class="text-center">FOTO</th>
                        <th>NAMA, NIP/{{ setting('sebutan_nip_desa') }}, NIK, TAG ID CARD</th>
                        <th nowrap>TEMPAT, <p>TANGGAL LAHIR</p>
                        </th>
                        <th>JENIS KELAMIN</th>
                        <th>AGAMA</th>
                        <th>PANGKAT / GOLONGAN</th>
                        <th>JABATAN</th>
                        <th>PENDIDIKAN TERAKHIR</th>
                        <th>NOMOR KEPUTUSAN PENGANGKATAN</th>
                        <th>TANGGAL KEPUTUSAN PENGANGKATAN</th>
                        <th>NOMOR KEPUTUSAN PEMBERHENTIAN</th>
                        <th>TANGGAL KEPUTUSAN PEMBERHENTIAN</th>
                        <th>MASA/PERIODE JABATAN</th>
                    </tr>
                </thead>
                <tbody id="dragable">
                </tbody>
            </table>
        </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('pengurus.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.kehadiran = $('#kehadiran').val();
                    }
                },
                columns: [{
                        data: 'drag-handle',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
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
                        data: 'foto',
                        name: 'foto',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'identitas',
                        name: 'identitas',
                        searchable: true,
                        orderable: false,
                        class: 'nowrap-left'
                    },
                    {
                        data: 'ttl',
                        name: 'ttl',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'sex',
                        name: 'sex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'agama',
                        name: 'agama',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pamong_pangkat',
                        name: 'pamong_pangkat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jabatan.nama',
                        name: 'jabatan.nama',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pendidikan_kk',
                        name: 'pendidikan_kk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pamong_nosk',
                        name: 'pamong_nosk',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_tglsk',
                        name: 'pamong_tglsk',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_nohenti',
                        name: 'pamong_nohenti',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_tglhenti',
                        name: 'pamong_tglhenti',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_masajab',
                        name: 'pamong_masajab',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.pamong_id)
                    $(row).addClass('dragable-handle');
                    var jabatan = @json($jabatanKadesSekdes);
                    if (data.jabatan_id == jabatan['0'] || data.jabatan_id == jabatan['1']) {
                        $(row).addClass('select-row');
                    }
                },
            });

            $('#status').select2().val({{ $default_status }}).trigger('change');

            $('#status').change(function() {
                TableData.draw()
            })

            // $('#kehadiran').select2().val(1).trigger('change');

            $('#kehadiran').change(function() {
                TableData.draw()
            })

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(0).visible(false);
                TableData.column(3).visible(false);
            }

            // harus diletakkan didalam blok ini, jika tidak maka object TableData tidak dikenal
            @include('admin.layouts.components.draggable', ['urlDraggable' => ci_route('pengurus.tukar')])
        });
    </script>
@endpush
