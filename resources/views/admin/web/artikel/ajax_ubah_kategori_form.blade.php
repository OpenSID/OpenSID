<form action="{{ $form_action }}" method="post" id="validasi">
    <div class='modal-body'>
        <div class="form-group">
            <label for="jenis_artikel">Jenis Artikel</label>
            <select class="form-control input-sm select2 required" id="jenis_artikel" style="width:100%;">
                <option @selected($tipe == 'dinamis')>Dinamis</option>
                <option @selected($tipe != 'dinamis')>Statis</option>
            </select>
        </div>
        <div class="form-group {{ $tipe == 'dinamis' ? '' : 'hide' }}" id="pilih_kategori_dinamis">
            <label for="kategori">Nama Kategori</label>
            <select class="form-control input-sm select2 {{ $tipe == 'dinamis' ? 'required' : '' }}" id="kategori" name="kategori" style="width:100%;">
                <option option value="">-- Pilih Kategori --</option>
                @foreach ($list_kategori as $kategori)
                    <option @selected($kategori_sekarang == $kategori['id']) value="{{ $kategori['id'] }}">{{ $kategori['kategori'] }}</option>
                    @foreach ($kategori['children'] as $sub_kategori)
                        <option @selected($kategori_sekarang == $sub_kategori['id']) value="{{ $sub_kategori['id'] }}">&emsp;{{ $sub_kategori['kategori'] }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group {{ $tipe != 'dinamis' ? '' : 'hide' }}" id="pilih_kategori_statis">
            <label for="kategori_statis">Nama Kategori</label>
            <select class="form-control input-sm select2 {{ $tipe != 'dinamis' ? 'required' : '' }}" id="kategori_statis" name="kategori_statis" style="width:100%;">
                <option option value="">-- Pilih Kategori --</option>
                @foreach (json_decode(setting('artikel_statis'), true) as $kategori)
                    <option @selected($tipe == $kategori) value="{{ $kategori }}">{{ ucwords($kategori) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
@include('admin.layouts.components.form_modal_validasi')
<script type="text/javascript">
    $(document).ready(function() {
        $('#jenis_artikel').change(function() {
            $('.form-group').removeClass('has-error');

            const isDinamis = $(this).val() === 'Dinamis';

            $('#pilih_kategori_dinamis')
                .toggleClass('hide', !isDinamis)
                .find('select').prop('disabled', !isDinamis).toggleClass('required', isDinamis);

            $('#pilih_kategori_statis')
                .toggleClass('hide', isDinamis)
                .find('select').prop('disabled', isDinamis).toggleClass('required', !isDinamis);
        });
    });
</script>
