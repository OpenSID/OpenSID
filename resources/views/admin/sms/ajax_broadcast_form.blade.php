@include('admin.layouts.components.validasi_form')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label for="grup">Grup Kontak</label>
            <select class="form-control input-sm select2 required" id="id_grup" name="id_grup">
                <option value="">Pilih Grup Kontak</option>
                @foreach ($grupKontak as $grup)
                    <option value="{{ $grup->id_grup }}">{{ $grup->nama_grup }} ( {{ $grup->anggota_count }} Anggota )</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="pesan">Isi Pesan</label>
            <textarea name="TextDecoded" class="form-control input-sm required" placeholder="Isi Pesan" rows="5"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-envelope-o'></i> Kirim</button>
    </div>
</form>
