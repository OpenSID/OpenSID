<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class='modal-body'>
        <div class="form-group row">
            <label class="control-label col-sm-3">Nama Jenis Garis</label>
            <div class="col-sm-7">
                <input type="hidden" name="tipe" value="{{ $tipe }}">
                <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" placeholder="Nama Jenis Garis" value="{{ $line['nama'] }}" />
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3">Jenis</label>
            <div class="col-sm-4">
                <select class="form-control input-sm required" id="jenis" name="jenis">
                    <option value="solid" @selected($line['jenis'] == 'solid')>Solid</option>
                    <option value="dotted" @selected($line['jenis'] == 'dotted')>Dotted</option>
                    <option value="dashed" @selected($line['jenis'] == 'dashed')>Dashed</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3">Warna Garis</label>
            <div class="col-sm-4">
                <div class="input-group my-colorpicker2">
                    <input type="text" id="color" name="color" class="form-control input-sm warna required" placeholder="#FFFFFF" value="{{ $line['color'] }}">
                    <div class="input-group-addon input-sm">
                        <i></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3">Tebal Garis</label>
            <div class="col-sm-4">
                <input
                    name="tebal"
                    class="form-control input-sm nomor_sk required"
                    id="tebal"
                    type="number"
                    value="{{ $line['tebal'] ?? 3 }}"
                    min="1"
                    max="10"
                />
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-sm-3"></label>
            <div class="col-sm-7"><br>
                <p id="showline"></p>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('.my-colorpicker2').colorpicker();
        $("#showline").hide();
        var j = document.getElementById("jenis");
        var t = document.getElementById("tebal");
        var c = document.getElementById("color");

        function show() {
            var isij = $('#validasi').find('#jenis').val()
            var isit = $('#validasi').find('#tebal').val()
            var isic = $('#validasi').find('#color').val()
            $('#showline').css({
                'display': 'block',
                'border-bottom': isit + 'px ' + isij + ' ' + isic
            });
        }
        j.onchange = show;
        t.onkeyup = show;
        t.onclick = show;
        c.onchange = show;
        show();
    })
</script>
@include('admin.layouts.components.form_modal_validasi')
