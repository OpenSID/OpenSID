@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label for="dusun">{{ ucwords(setting('sebutan_dusun')) }} </label>
            <select id="dusun" class="form-control input-sm select2">
                <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
                @foreach ($wilayah as $keyDusun => $dusun)
                    <option value="{{ $keyDusun }}">{{ $keyDusun }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="rw">RW</label>
            <select id="rw" class="form-control input-sm required select2">
                <option class="placeholder" value="">Pilih RW</option>
                @foreach ($wilayah as $keyDusun => $dusun)
                    <optgroup value="{{ $keyDusun }}" label="{{ $keyDusun }}">
                        @foreach ($dusun as $keyRw => $rw)
                            <option value="{{ $keyDusun }}__{{ $keyRw }}">{{ $keyRw }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="rt">RT</label>
            <select id="id_cluster" name="id_cluster" class="form-control input-sm required select2">
                <option class="placeholder" value="">Pilih RT </option>
                @foreach ($wilayah as $keyDusun => $dusun)
                    @foreach ($dusun as $keyRw => $rw)
                        <optgroup value="{{ $keyDusun }}__{{ $keyRw }}" label="{{ $keyRw }}">
                            @foreach ($rw as $rt)
                                <option value="{{ $rt->id }}">{{ $rt->rt }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group hide" id="checkbox_div">
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>
            Simpan</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#validasi #dusun').change(function() {
            let _label = $(this).find('option:selected').val()
            $('#validasi #rw').find(`optgroup`).prop('disabled', 1)
            if ($(this).val()) {
                $('#validasi #rw').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
            }
            $('#validasi #rw').val('')
            $('#validasi #rw').trigger('change')
        })

        $('#validasi #rw').change(function() {
            let _label = $(this).find('option:selected').val()
            $('#validasi #id_cluster').find(`optgroup`).prop('disabled', 1)
            if ($(this).val()) {
                $('#validasi #id_cluster').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
            }
            $('#validasi #id_cluster').val('')
            $('#validasi #id_cluster').trigger('change')
        })

        // copy id_rb terpilih ke form ini
        let _clone = $('#tabeldata').find('input[name="id_cb[]"]:checked').clone()
        $('#checkbox_div').append(_clone)
    });
</script>
