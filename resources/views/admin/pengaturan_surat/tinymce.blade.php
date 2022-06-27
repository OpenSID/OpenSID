@include('admin.pengaturan_surat.asset_tinymce')

<div class="tab-pane" id="template-surat">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <div class="form-group">
            <textarea name="template_desa" class="form-control input-sm editor required">{{ $suratMaster->template_desa ?? $suratMaster->template }}</textarea>
        </div>
    </div>
</div>
<div class="tab-pane" id="kode-isian">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tbody>
                    <tr style="font-weight: bold;">
                        <td>Nama</td>
                        <td>Deskripsi</td>
                        <td>Aksi</td>
                    </tr>
                    @forelse ($kodeIsian as $key => $value)
                        <tr class="duplikasi" id="gandakan-{{ $key }}">
                            @if ($value->tipe === 'text')
                                <td><input type="text" name="nama_kode[]" class="form-control input-sm"
                                        value="{{ $value->nama }}"></td>
                                <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm"
                                        value="{{ $value->deskripsi }}"></td>
                                <td width="1%">
                                    <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                            class='fa fa-trash-o'></i></button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="duplikasi" id="gandakan-0">
                            <td><input type="text" name="nama_kode[]" class="form-control input-sm"></td>
                            <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm"></td>
                            <td width="1%">
                                <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                        class='fa fa-trash-o'></i></button>
                            </td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="3">
                            <button type="button" class="btn btn-success btn-sm btn-block tambah-kode"><i
                                    class="fa fa-plus"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#gandakan-0').find("button").hide();
            var counter = $(".tambah-kode").data("counter");
            var key = $("#gandakan-0").length;

            $('.tambah-kode').on('click', function() {
                var editElm;
                counter++;
                key++;
                $("#gandakan-0").clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', 'gandakan-' + key)
                            .find('select')
                            .end();
                    });

                if ($("#gandakan-" + (key - 1)).length) {
                    $("#gandakan-" + (key - 1)).after(editElm);
                } else {
                    $("#gandakan-0").after(editElm);
                }
                $("#gandakan-" + key).find('input').val('');

                $('.duplikasi').find("button").show();
                $('#gandakan-0').find("button").hide();
            });

            $('.hapus-kode').on('click', function() {
                $(this).parents('.duplikasi').remove();
            });
        });
    </script>
@endpush
