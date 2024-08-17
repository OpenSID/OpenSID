<style>
    .title {
        color: #000
    }
</style>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header with-border">
            <a href="{{ ci_route('dtks') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Dtks"><i class="fa fa-arrow-circle-o-left"></i>Kembali Ke Data DTKS</a>
        </div>
        <div class="box-body tab-content" style="padding-left:30px; padding-right:30px">
            <table>
                <tr>
                    <td>No Kartu Rumah Tangga(KRT)</td>
                    <td>:</td>
                    <td>{{ $dtks->rtm->no_kk }}</td>
                    @if ($dtks->jumlah_keluarga > 1)
                        <td rowspan="4">
                            <a href="#" id="btn-modal-keluarga-lainnya" data-remote="false" data-toggle="modal" data-target="#modal-keluarga-lainnya" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                                    class="fa fa-plus"
                                ></i> Data Keluarga dalam rumah tangga ini</a>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Nama KRT</td>
                    <td>:</td>
                    <td>{{ $dtks->kepala_rumah_tangga->nama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $dtks->alamat }}</td>
                </tr>
                <tr>
                    <td>Terakhir diubah</td>
                    <td>:</td>
                    <td>{{ $dtks->updated_at }}</td>
                </tr>
            </table>
            @if ($dtks->jumlah_keluarga > 1)
                <div
                    class="modal fade"
                    id="modal-keluarga-lainnya"
                    style="overflow: scroll;"
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="myModalLabel"
                    aria-hidden="true"
                >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Data Keluarga dalam rumah tangga ini</h4>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <tr>
                                        <th>Kepala Rumah Tangga</th>
                                        <th>No KK</th>
                                        <th>Kepala Keluarga</th>
                                        <th>Jumlah Anggota</th>
                                        <th>Aksi</th>
                                    </tr>
                                    @foreach ($dtks->all_dtks_id as $item)
                                        <tr>
                                            {{-- <td>{{$dtks->all_dtks_id[1]}}</td> --}}
                                            <td>{{ $item ? $item->rtm->kepalaKeluarga->nama : '' }}</td>
                                            <td>{{ $item ? $item->keluarga->no_kk : '' }}</td>
                                            <td>{{ $item ? $item->keluarga->kepalaKeluarga->nama : '' }}</td>
                                            <td><a href="{{ ci_route('dtks.listAnggota') }}/{{ $item->id }}" title="Lihat Nama Anggota" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Daftar Anggota">{{ $item->dtks_anggota_count }}</a></td>
                                            <td>
                                                <a href="{{ ci_route('dtks.form', $item->id) }}" target="__blank" class="btn btn-primary btn-sm">
                                                    Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <hr>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                    <li><a href="#bagian-1" data-toggle="tab" id="nav-bagian-1"><strong>I. KETERANGAN TEMPAT</strong></a></li>
                    <li><a href="#bagian-2" data-toggle="tab" id="nav-bagian-2"><strong>II. KETERANGAN PETUGAS</strong></a></li>
                    <li><a href="#bagian-3" data-toggle="tab" id="nav-bagian-3"><strong>III. KETERANGAN PERUMAHAN</strong></a></li>
                    <li><a href="#bagian-4" data-toggle="tab" id="nav-bagian-4"><strong>IV. KETERANGAN SOSIAL EKONOMI ANGGOTA KELUARGA</strong></a></li>
                    <li><a href="#bagian-5" data-toggle="tab" id="nav-bagian-5"><strong>V. KEIKUTSERTAAN PROGRAM, KEPEMILIKAN ASET, DAN LAYANAN</strong></a></li>
                    <li><a href="#bagian-6" data-toggle="tab" id="nav-bagian-6"><strong>VI. CATATAN</strong></a></li>
                    <li><a href="#bagian-7" data-toggle="tab" id="nav-bagian-7"><strong>LAMPIRAN FOTO</strong></a></li>
                </ul>
            </div>
            <hr>
            <div class="tab-pane" id="bagian-1">
                @include('admin.dtks.2.form_isian_tab1')
            </div>
            <div class="tab-pane" id="bagian-2">
                @include('admin.dtks.2.form_isian_tab2')
            </div>
            <div class="tab-pane" id="bagian-3">
                @include('admin.dtks.2.form_isian_tab3')
            </div>
            <div class="tab-pane" id="bagian-4">
                @include('admin.dtks.2.form_isian_tab4')
            </div>
            <div class="tab-pane" id="bagian-5">
                @include('admin.dtks.2.form_isian_tab5')
            </div>
            <div class="tab-pane" id="bagian-6">
                @include('admin.dtks.2.form_isian_tab6')
            </div>
            <div class="tab-pane" id="bagian-7">
                @include('admin.dtks.2.form_isian_tab7')
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        function show_when_otherwise_hide(condition, element_ids_to_show, element_ids_to_hide) {
            if (condition) {
                element_ids_to_show.forEach(function(el) {
                    $('#' + el).show();
                    // $('#' + el).find('input, select').each(function(index, el){
                    //     if( ! $(el).hasClass('select2-search__field')){
                    //         $(el).addClass('required');
                    //     }
                    // })
                });
            } else {
                element_ids_to_hide.forEach(function(el) {
                    $('#' + el).hide();
                    // $('#' + el).find('input, select').each(function(index, el){
                    //     if( ! $(el).hasClass('select2-search__field')){
                    //         $(el).removeClass('required');
                    //     }
                    // })
                });
            }
        };
        $(document).ready(function() {
            $.each($(".tab-content .tab-pane"), function(index, val) {
                var id = $(val).attr('id');
                $(`#nav-${id}`).on('click', function() {
                    $('#judul-bagian').text($(`#nav-${id} strong`).text());
                });
                if (index == 0) {
                    $(`#nav-${id}`).trigger("click");
                }
            });
        });
    </script>
@endpush
