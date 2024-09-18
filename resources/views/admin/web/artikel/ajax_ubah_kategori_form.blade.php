<form action="{{ $form_action }}" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nama">Nama Kategori</label>
                            <select class="form-control input-sm required" id="kategori" name="kategori" style="width:100%;">
                                <option option value="">-- Pilih Kategori --</option>
                                @foreach ($list_kategori as $kategori)
                                    <option @selected($kategori_sekarang == $kategori['id']) value="{{ $kategori['id'] }}">{{ $kategori['kategori'] }}</option>
                                    @foreach ($kategori['children'] as $sub_kategori)
                                        <option @selected($kategori_sekarang == $sub_kategori['id']) value="{{ $sub_kategori['id'] }}">&emsp;{{ $sub_kategori['kategori'] }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
        </div>
    </div>
</form>
@include('admin.layouts.components.form_modal_validasi')
