@include('admin.layouts.components.validasi_form')

<form id="validasi" action="{{ $form_action }}" method="POST">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="kategori">Nama Pelapak</label>
            <select class="form-control input-sm select2 required" id="id_pend" name="id_pend" onchange="tampil_telepon($(this).find(':selected'))">
                <option value="">-- Silahkan Cari NIK - Nama Penduduk --</option>
                @foreach ($list_penduduk as $penduduk)
                    <option value="{{ $penduduk->id }}" @selected($main->id_pend == $penduduk->id) data-telepon="{{ $penduduk->telepon }}">{{ $penduduk->nik . ' - ' . $penduduk->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="nama">No. Telepon</label>
            <input class="form-control input-sm number required" type="text" name="telepon" id="telepon" placeholder="Nomer Telepon" value="{{ $main->telepon }}" />
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social  btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social  btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>

<script type="text/javascript">
    function tampil_telepon(elem) {
        var telepon = elem.data('telepon');
        $('#telepon').val(telepon);
    }
</script>
