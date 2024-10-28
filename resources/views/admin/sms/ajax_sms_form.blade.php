@include('admin.layouts.components.validasi_form')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="hp">Telepon Tujuan</label>
            <input name="DestinationNumber" class="form-control input-sm required bilangan" type="text" value="{{ $sms['DestinationNumber'] }}" readonly></input>
        </div>
        <div class="form-group">
            <label class="control-label" for="pesan">Isi Pesan</label>
            <textarea @if ($tipe == 3) name="TextDecoded" @endif class="form-control input-sm required" placeholder="Isi Pesan" rows="5" @disabled($tipe != 3)>{{ $sms['TextDecoded'] }}</textarea>
        </div>
        @if ($tipe == 1)
            <div class="form-group">
                <label class="control-label" for="balas">Balas Pesan</label>
                <textarea name="TextDecoded" class="form-control input-sm required" placeholder="Isi Pesan" rows="5"></textarea>
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-envelope-o"></i> Kirim</button>
    </div>
</form>
