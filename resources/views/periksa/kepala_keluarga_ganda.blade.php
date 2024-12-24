<div class="row">
    <div class="col-md-4">
        <div class='form-group'>
            <label for="kk_level">Hubungan Dalam Keluarga</label>
            <select id="kk_level" class="form-control input-sm required select2" name="kk_level">
                @foreach ($hubungan as $key => $value)
                    <option value="{{ $key }}">
                        {{ strtoupper($value) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <button class="btn btn-sm btn-primary" onclick="ubahSHDK(this)">Simpan</button>
    </div>
</div>

<script type="text/javascript">
    function ubahSHDK(elm) {
        $.post('periksaKepalaKeluargaGanda/ubahShdk', {
            id: {{ $id }},
            kk_level: $('#kk_level').val(),
            {{ $ci->security->get_csrf_token_name() }}: '{{ $ci->security->get_csrf_hash() }}'
        }, function(data) {
            let _message = 'Data SHDK gagal diubah'
            let _messageClass = 'danger'
            if (data.status) {
                let _modal = $(elm).closest('.modal')
                _modal.find('button.close').click()
                $('tr[data-kepala-keluarga-ganda={{ $id }}]').find('td:last').html(
                    '<button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sudah diperbaiki</button>'
                )
                _message = 'Data SHDK berhasil diubah'
                _messageClass = 'success'
            }
            $('#info-kepala-keluarga-ganda').html(`<div class="alert alert-${_messageClass}">${_message}</div>`)
        }, 'json')
    }
</script>
