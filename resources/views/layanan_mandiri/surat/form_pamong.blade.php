<div class="form-group tdk-permohonan">
    <label class="col-sm-3 control-label">Bertanda Tangan</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm select2" id="atas_nama" name="pilih_atas_nama" onchange="ganti_ttd($(this).val());">
            @foreach ($atas_nama as $key => $data)
                <option value="{{ $key }}">{{ $data }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group tdk-permohonan">
    <label class="col-sm-3 control-label">{{ ucwords(setting('sebutan_pemerintah_desa')) }}</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control required input-sm" id="pamong" name="pamong_id">
            <option value='' selected="selected">-- {{ 'Pilih ' . ucwords(setting('sebutan_pemerintah_desa')) }} --</option>
            @foreach ($pamong as $data)
                <option
                    value="{{ $data->pamong_id }}"
                    data-jenis="{{ $data->jenis }}"
                    data-jabatan="{{ trim($data->jabatan->nama) }}"
                    data-nip="{{ $data->pamong_nip }}"
                    data-niap="{{ $data->pamong_niap }}"
                    data-ttd="{{ $data->pamong_ttd }}"
                    data-ub="{{ $data->pamong_ub }}"
                >
                    {{ $data->pamong_nip ? 'NIP : ' . ($data->pamong_nip ?? '-') . ' | ' : setting('sebutan_nip_desa') . ' : ' . ($data->pamong_niap ?? '-') . ' | ' }}
                    {{ $data->pamong_nama . ' | ' . $data->jabatan->nama }}
                </option>
            @endforeach
        </select>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#atas_nama').change();
        });

        function ganti_ttd(atas_nama) {
            if (typeof atas_nama !== 'string' || !atas_nama) {
                return;
            }

            const $pamong = $('#pamong');
            const $options = $pamong.find('option');

            $options.show();

            if (atas_nama.includes('a.n')) {
                const ub = $options.filter("[data-ttd='1']").val() || '';
                $pamong.val(ub).prop('disabled', true);
            } else if (atas_nama.includes('u.b')) {
                $pamong.val('').prop('disabled', false);
                $options.filter("[data-jenis='1'],[data-ttd='1']").hide();
            } else {
                const jenis1 = $options.filter("[data-jenis='1']").val() || '';
                $pamong.val(jenis1).prop('disabled', true);
            }

            $pamong.trigger('change');
        }
    </script>
@endpush
