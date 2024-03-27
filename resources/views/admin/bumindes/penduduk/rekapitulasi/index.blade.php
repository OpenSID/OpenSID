@push('css')
    <style>
        .text-left {
            text-align: left !important;
        }
    </style>
@endpush
<div class="box box-info">
    <div class="box-header with-border">
        <a
            href="{{ route('bumindes_penduduk_rekapitulasi.dialog_cetak', ['aksi' => 'cetak']) }}"
            class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Buku Rekapitulasi Penduduk Desa"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Buku Rekapitulasi Penduduk Desa"
        >
            <i class="fa fa-print"></i>Cetak
        </a>
        <a
            href="{{ site_url("{$controller}/dialog_cetak/unduh") }}"
            class="btn btn-social bg-navy btn-sm
			btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Buku Rekapitulasi Penduduk Desa"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh Buku Rekapitulasi Penduduk Desa"
        ><i class="fa fa-download"></i>Unduh</a>
        <a
            href="{{ site_url($controller . '/dialog_cetak/pdf') }}"
            title="Laporan PDF Buku Rekapitulasi Penduduk Desa"
            class="btn btn-social bg-green btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Laporan PDF Buku Rekapitulasi Penduduk Desa"
        ><i class="fa fa-file-pdf-o"></i> Laporan PDF</a>
    </div>
    <div class="box-body">
        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <form id="mainform" name="mainform" method="post">
                <div class="row">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2 " name="tahun" id="tahun">
                            <option value="">Pilih Tahun</option>
                            @foreach (tahun($tahun) as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" name="filter_bulan" id="bulan">
                            <option value="">Pilih Bulan</option>
                            @foreach (bulan() as $idx => $nama_bulan)
                                <option value="{{ $idx }}">{{ $nama_bulan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-hover" id="tabeldata">

                                <thead class="bg-gray color-palette">
                                    <tr>
                                        <th rowspan="4" nowrap>NOMOR URUT</th>
                                        <th rowspan="4" nowrap>NAMA DUSUN / LINGKUNGAN</th>
                                        <th colspan="7" nowrap>JUMLAH PENDUDUK AWAL BULAN</th>
                                        <th colspan="8" nowrap>TAMBAHAN BULAN INI</th>
                                        <th colspan="8" nowrap>PENGURANGAN BULAN INI</th>
                                        <th rowspan="2" colspan="7" nowrap>JML PENDUDUK AKHIR BULAN</th>
                                        <th rowspan="4" nowrap>KET</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" nowrap>WNA</th>
                                        <th colspan="2" nowrap>WNI</th>
                                        <th rowspan="3" nowrap>JML KK</th>
                                        <th rowspan="3" nowrap>JML ANGGOTA KELUARGA</th>
                                        <th rowspan="3" nowrap>JML JIWA (7+8)</th>
                                        <th colspan="4" nowrap>LAHIR</th>
                                        <th colspan="4" nowrap>DATANG</th>
                                        <th colspan="4" nowrap>MENINGGAL</th>
                                        <th colspan="4" nowrap>PINDAH</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" nowrap>L</th>
                                        <th rowspan="2" nowrap>P</th>
                                        <th rowspan="2" nowrap>L</th>
                                        <th rowspan="2" nowrap>P</th>
                                        <th colspan="2" nowrap>WNA</th>
                                        <th colspan="2" nowrap>WNI</th>
                                        <th colspan="2" nowrap>WNA</th>
                                        <th colspan="2" nowrap>WNI</th>
                                        <th colspan="2" nowrap>WNA</th>
                                        <th colspan="2" nowrap>WNI</th>
                                        <th colspan="2" nowrap>WNA</th>
                                        <th colspan="2" nowrap>WNI</th>
                                        <th colspan="2" nowrap>WNA</th>
                                        <th colspan="2" nowrap>WNI</th>
                                        <th rowspan="2" nowrap>JML KK</th>
                                        <th rowspan="2" nowrap>JML ANGGOTA KELUARGA</th>
                                        <th rowspan="2" nowrap>JML JIWA (30+31)</th>
                                    </tr>
                                    <tr>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>P</th>
                                    </tr>
                                    <tr class="border thick">
                                        <?php for ($i = 1; $i <= 33; $i++): ?>
                                        <th>
                                            {{ $i }}
                                        </th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray color-palette">
                                        <th class="padat" colspan="2">TOTAL</th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                        <th class="padat"></th>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
                    url: "{{ route('bumindes_penduduk_rekapitulasi.datatables') }}",
                    data: function(req) {
                        req.tahun = $('#tahun').val();
                        req.bulan = $('#bulan').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DUSUN',
                        name: 'DUSUN',
                        className: 'text-left',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'WNA_L_AWAL',
                        name: 'WNA_L_AWAL',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_P_AWAL',
                        name: 'WNA_P_AWAL',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_L_AWAL',
                        name: 'WNI_L_AWAL',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_P_AWAL',
                        name: 'WNI_P_AWAL',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'KK_JLH',
                        name: 'KK_JLH',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'KK_ANG_KEL',
                        name: 'KK_ANG_KEL',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'JLH_JIWA_1',
                        name: 'JLH_JIWA_1',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_L_TAMBAH_LAHIR',
                        name: 'WNA_L_TAMBAH_LAHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_P_TAMBAH_LAHIR',
                        name: 'WNA_P_TAMBAH_LAHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_L_TAMBAH_LAHIR',
                        name: 'WNI_L_TAMBAH_LAHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_P_TAMBAH_LAHIR',
                        name: 'WNI_P_TAMBAH_LAHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_L_TAMBAH_MASUK',
                        name: 'WNA_L_TAMBAH_MASUK',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_P_TAMBAH_MASUK',
                        name: 'WNA_P_TAMBAH_MASUK',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_L_TAMBAH_MASUK',
                        name: 'WNI_L_TAMBAH_MASUK',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_P_TAMBAH_MASUK',
                        name: 'WNI_P_TAMBAH_MASUK',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_L_KURANG_MATI',
                        name: 'WNA_L_KURANG_MATI',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_P_KURANG_MATI',
                        name: 'WNA_P_KURANG_MATI',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_L_KURANG_MATI',
                        name: 'WNI_L_KURANG_MATI',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_P_KURANG_MATI',
                        name: 'WNI_P_KURANG_MATI',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_L_KURANG_KELUAR',
                        name: 'WNA_L_KURANG_KELUAR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_P_KURANG_KELUAR',
                        name: 'WNA_P_KURANG_KELUAR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_L_KURANG_KELUAR',
                        name: 'WNI_L_KURANG_KELUAR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_P_KURANG_KELUAR',
                        name: 'WNI_P_KURANG_KELUAR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_L_AKHIR',
                        name: 'WNA_L_AKHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNA_P_AKHIR',
                        name: 'WNA_P_AKHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_L_AKHIR',
                        name: 'WNI_L_AKHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'WNI_P_AKHIR',
                        name: 'WNI_P_AKHIR',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'KK_AKHIR_JML',
                        name: 'KK_AKHIR_JML',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'KK_AKHIR_ANG_KEL',
                        name: 'KK_AKHIR_ANG_KEL',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'JLH_JIWA_2',
                        name: 'JLH_JIWA_2',
                        searchable: true,
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            if (type === 'display' && parseFloat(data) === 0) {
                                return '-';
                            }
                            return data;
                        }
                    },
                    {
                        defaultContent: '-',
                        className: 'text-center',
                        orderable: false
                    },
                ],
                order: [
                    // [1, 'asc']
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    for (var i = 2; i < api.columns().count(); i++) {
                        var columnData = api.column(i, {
                            page: 'current'
                        }).data();
                        var total = columnData.reduce(function(a, b) {
                            var a = isNaN(a) ? 0 : a;
                            return a + parseFloat(b);
                        }, 0);

                        total = isNaN(total) ? 0 : total;

                        $(api.column(i).footer()).html(total == 0 ? '-' : total);
                    }
                }
            });


            $('#tahun').change(function() {
                TableData.draw()
            })

            $('#bulan').change(function() {
                TableData.draw()
            })

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
