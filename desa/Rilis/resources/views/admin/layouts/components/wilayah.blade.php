<div class="{{ $colDusun ?? 'col-sm-2' }}">
    @if ($labelWilayah)
        <label for="dusun">{{ ucwords(setting('sebutan_dusun')) }}</label>
    @endif
    <select id="dusun" class="form-control input-sm select2" @disabled($disableFilter ?? false)>
        <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
        @foreach ($wilayah as $keyDusun => $dusun)
            <option value="{{ $keyDusun }}">{{ $keyDusun }}</option>
        @endforeach
    </select>
</div>
<div class="{{ $colRw ?? 'col-sm-2' }}">
    @if ($labelWilayah)
        <label for="rw">RW</label>
    @endif
    <select id="rw" class="form-control input-sm select2" @disabled($disableFilter ?? false)>
        <option value="">Pilih RW</option>
        @foreach ($wilayah as $keyDusun => $dusun)
            <optgroup value="{{ $keyDusun }}" label="{{ ucwords(setting('sebutan_dusun')) . ' ' . $keyDusun }}">
                @foreach ($dusun as $keyRw => $rw)
                    <option value="{{ $keyDusun }}__{{ $keyRw }}">{{ $keyRw }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>
<div class="{{ $colRt ?? 'col-sm-2' }}">
    @if ($labelWilayah)
        <label for="rt">RT</label>
    @endif
    <select id="rt" class="form-control input-sm select2" @disabled($disableFilter ?? false)>
        <option value="">Pilih RT</option>
        @foreach ($wilayah as $keyDusun => $dusun)
            @foreach ($dusun as $keyRw => $rw)
                <optgroup value="{{ $keyDusun }}__{{ $keyRw }}" label="{{ 'RW ' . $keyRw }}">
                    @foreach ($rw as $rt)
                        <option value="{{ $rt->id }}">{{ $rt->rt }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        @endforeach
    </select>
</div>
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#dusun').change(function() {
                let _label = $(this).find('option:selected').val()
                $('#rw').find(`optgroup`).prop('disabled', 1)
                if ($(this).val()) {
                    $('#rw').closest('div').show()
                    $('#rw').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
                } else {
                    $('#rw').closest('div').hide()
                    $('#rw').find(`optgroup`).prop('disabled', 1)
                }
                $('#rw').val('')
                $('#rw').trigger('change')
            })

            $('#rw').change(function() {
                let _label = $(this).find('option:selected').val()
                $('#rt').find(`optgroup`).prop('disabled', 1)
                if ($(this).val()) {
                    $('#rt').closest('div').show()
                    $('#rt').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
                } else {
                    $('#rt').closest('div').hide()
                    $('#rt').find(`optgroup`).prop('disabled', 1)
                }
            })
            $('#dusun').trigger('change')
        })
    </script>
@endpush
