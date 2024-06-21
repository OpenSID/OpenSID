<div class="form-group form-horizontal">
    <select class="form-control input-sm syarat required" name="syarat[{{ $syarat_id }}]" onchange="cek_perhatian($(this));">
        <option value=""> -- Pilih dokumen yang melengkapi syarat -- </option>
        @foreach ($dokumen as $data)
            @if ($data['id_syarat'] == $syarat_id)
                <option value="{{ $data['id'] }}" {{ $data['id'] == $syarat_permohonan[$syarat_id] ? 'selected' : '' }}>{{ $data['nama'] }}</option>
            @endif
        @endforeach
        @if ($cek_anjungan)
            <option value="-1" {{ '-1' == $syarat_permohonan[$syarat_id] ? 'selected' : '' }}>Bawa bukti fisik ke Kantor Desa</option>
        @endif
    </select>
    <i class="fa fa-exclamation-triangle text-red perhatian" style="display: none; padding-left: 10px; font-weight: bold;">&nbsp;Perhatian!</i>
</div>
