<div class="box box-info">
    <div class="box-header with-border">
        <x-tambah-button :url="$controller . '/form'" />

        <x-hapus-button
            :url="$controller . '/delete'"
            :confirmDelete="true"
            :selectData="true"
            judul="Hapus Data Terpilih"
        />

        @php
            $listBagan = [
                [
                    'url'   => "{$controller}/bagan",
                    'judul' => 'Bagan Tanpa BPD',
                    'icon'  => 'fa fa-sitemap',
                    'modal' => false
                ],
                [
                    'url'   => "{$controller}/bagan/bpd",
                    'judul' => 'Bagan Dengan BPD',
                    'icon'  => 'fa fa-sitemap',
                    'modal' => false
                ],
                [
                    'url'   => "{$controller}/atur_bagan",
                    'judul' => 'Atur Struktur Bagan',
                    'icon'  => 'fa fa-sitemap',
                    'modal' => true
                ]
            ];
        @endphp

        <x-split-button
            judul="Bagan Organisasi"
            :list="$listBagan"
            :icon="'fa fa-arrow-circle-down'"
            :type="'bg-olive'"
            :target="true"
        />

        @php
            $listCetakUnduh = [
                [
                    'url'   => "{$controller}/dialog/cetak",
                    'judul' => 'Cetak',
                    'title' => 'Cetak Buku Pemerintah Desa',
                    'icon'  => 'fa fa-print',
                    'modal' => true
                ],
                [
                    'url'   => "{$controller}/dialog/unduh",
                    'judul' => 'Unduh',
                    'title' => 'Unduh Buku Pemerintah Desa',
                    'icon'  => 'fa fa-download',
                    'modal' => true
                ]
            ];
        @endphp

        <x-split-button
            judul="Cetak/Unduh"
            :list="$listCetakUnduh"
            :icon="'fa fa-arrow-circle-down'"
            :type="'bg-purple'"
            :target="true"
        />

        <x-btn-button judul="Jabatan" icon="fa fa-list" type="bg-navy" :url="$controller . '/jabatan'" />

        @if (can('b', 'jam-kerja') || can('b', 'hari-libur') || can('b', 'rekapitulasi') || can('b', 'kehadiran-pengaduan'))

            @php
                $listKehadiran = [];

                if(can('b', 'jam-kerja'))
                {   
                    $listKehadiran[] = [
                        'url'   => "kehadiran_jam_kerja",
                        'judul' => 'Jam Kerja',
                        'icon'  => 'fa fa-clock-o',
                        'modal' => false
                    ];
                }

                if(can('b', 'hari-libur'))
                {   
                    $listKehadiran[] = [
                        'url'   => "kehadiran_hari_libur",
                        'judul' => 'Hari Libur',
                        'icon'  => 'fa fa-calendar',
                        'modal' => false
                    ];
                }

                if(can('b', 'rekapitulasi'))
                {   
                    $listKehadiran[] = [
                        'url'   => "kehadiran_rekapitulasi",
                        'judul' => 'Kehadiran',
                        'icon'  => 'fa fa-list',
                        'modal' => false
                    ];
                }

                if(can('b', 'kehadiran-pengaduan'))
                {   
                    $listKehadiran[] = [
                        'url'   => "kehadiran_pengaduan",
                        'judul' => 'Pengaduan',
                        'icon'  => 'fa fa-exclamation',
                        'modal' => false
                    ];
                }
            @endphp

            <x-split-button
                judul="Kehadiran"
                :list="$listKehadiran"
                :icon="'fa fa-arrow-circle-down'"
                :type="'bg-orange'"
                :target="true"
            />
        @endif

    </div>
    <div class="box-body">
        <div class="row mepet">
            <div class="col-sm-2">
                <select id="status" class="form-control input-sm select2">
                    <option value="">Pilih Status</option>
                    @foreach ($status as $key => $item)
                        <option @selected($default_status == $key) value="{{ $key }}">{{ $item }}</option>
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
                        data: 'pamong_sex',
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

            $('#status, #kehadiran').change(function() {
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
