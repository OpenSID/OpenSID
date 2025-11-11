@include('admin.layouts.components.form_modal_validasi')

<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group mt-2">
            <label>Nomor Kartu Keluarga (KK) Baru
                <code id="tampil_nokk" style="display:none;">(Sementara)</code>
            </label>
            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                    <input type="checkbox" id="nokk_sementara" title="Centang jika belum memiliki No. KK">
                </span>
                <input id="no_kk" name="no_kk" class="form-control input-sm required no_kk" type="text" placeholder="Nomor KK"
                    value="{{ $no_kk }}">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th style="width:5%">Pilih</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Hubungan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($main as $data)
                        @php $isKepalaBaru = $data->id == $id; @endphp
                        <tr @if($isKepalaBaru) style="background:#e8f5e9;" @endif>
                            <td class="text-center">
                                <input type="checkbox" name="anggota[]" value="{{ $data->id }}"
                                    {{ $isKepalaBaru ? 'checked disabled' : '' }}>
                                @if($isKepalaBaru)
                                    <input type="hidden" name="nik_kepala" value="{{ $data->id }}">
                                @endif
                            </td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>
                                @if($isKepalaBaru)
                                    <select name="kk_level[{{ $data->id }}]" class="form-control input-sm select2" disabled>
                                        <option value="{{ \App\Enums\SHDKEnum::KEPALA_KELUARGA }}">{{ \App\Enums\SHDKEnum::valueToUpper(\App\Enums\SHDKEnum::KEPALA_KELUARGA) }}</option>
                                    </select>
                                @else
                                    <select name="kk_level[{{ $data->id }}]" class="form-control input-sm select2">
                                        <option value="">-- Pilih Hubungan --</option>
                                        @foreach ($hubungan as $key => $val)
                                            {{-- Abaikan pilihan Kepala Keluarga untuk selain kepala --}}
                                            @if($key != 1)
                                                <option value="{{ $key }}"
                                                    {{ $data->kk_level == $key ? 'selected' : '' }}>
                                                    {{ $val }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-info btn-sm">
            <i class='fa fa-check'></i> Simpan
        </button>
    </div>
</form>

<script type="text/javascript">
$(function() {
    $('.select2').select2({ width: '100%' });

    $('#nokk_sementara').change(function() {
        var sementara = '{{ $nokk_sementara }}';
        var asli = '{{ $no_kk }}';

        if ($(this).prop('checked')) {
            $('#no_kk').val(sementara).prop('readonly', true);
            $('#tampil_nokk').show();
        } else {
            $('#no_kk').val(asli).prop('readonly', false);
            $('#tampil_nokk').hide();
        }
    }).change();

    $('form').on('reset', function() {
        setTimeout(function() {
            $('#nokk_sementara').trigger('change');
        }, 0);
    });
});
</script>
