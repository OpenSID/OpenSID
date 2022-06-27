<div class="form-group tdk-permohonan">
    <label class="col-sm-3 control-label">Tertanda Atas Nama</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm select2" name="pilih_atas_nama" onchange="ganti_ttd($(this).val());	">
            <option value="">-- Atas Nama --</option>
            @foreach ($atas_nama as $data)
                <option value="{{ $data }}" @selected($data === $_SESSION['post']['atas_nama'])>{{ $data }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group tdk-permohonan">
    <label class="col-sm-3 control-label">{{ SebutanDesa('Staf Pemerintah [Desa]') }}</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control required input-sm select2" id="pamong" name="nama_pamong"
            onchange="ambil_pamong($(this).find(':selected'))">
            <option value='' selected="selected">-- {{ SebutanDesa('Pilih Staf Pemerintah [Desa]') }} --
            </option>
            @foreach ($pamong as $data)
                {{ $tmp_nip = trim($data['pamong_nip'], '-') }}
                <option value="{{ $data['pamong_nama'] }}" data-jabatan="{{ trim($data['jabatan']) }}"
                    @if ($data['pamong_ttd'] == 1) $pamong_nip = @php $data['pamong_nip']; $pamong_niap = $data['pamong_niap']; @endphp selected @endif
                    data-nip="{{ $data['pamong_nip'] }}" data-niap="{{ $data['pamong_niap'] }}"
                    data-pamong-id="{{ $data['pamong_id'] }}" data-ttd="{{ $data['pamong_ttd'] }}"
                    data-ub="{{ $data['pamong_ub'] }}">
                    {{ $data['pamong_nama'] }} ({{ $data['jabatan'] }}) @if (!empty($tmp_nip))
                        NIP: {{ $data['pamong_nip'] }}
                    @endif
                </option>
            @endforeach
        </select>
        <input name="nip_pamong" id="pamong_nip" type="hidden" value="{{ $pamong_nip }}" />
        <input name="niap_pamong" id="pamong_niap" type="hidden" value="{{ $pamong_niap }}" />
        <input name="pamong_id" id="pamong_id" type="hidden" value="{{ $pamong_id }}" />
    </div>
</div>
<div class="form-group tdk-permohonan">
    <label for="jabatan" class="col-sm-3 control-label">Menjabat Sebagai</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm required" id="jabatan" name="jabatan" readonly>
            <option value='' selected="selected">-- Pilih Jabatan--</option>
            @foreach ($pamong as $data)
                <option @if ($data['pamong_ttd'] == 1) selected @endif>{{ $data['jabatan'] }}</option>
            @endforeach
        </select>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#pamong').change();
        });

        function ganti_ttd(atas_nama) {
            if (atas_nama.includes('a.n')) {
                ub = $("#pamong option[data-ub='1']").val();
                if (ub) {
                    $('#pamong').val(ub);
                    $('#pamong').change();
                } else {
                    $('#pamong').val('');
                    $('#jabatan').val('');
                }
            } else if (atas_nama.includes('u.b')) {
                $('#pamong').val('');
                $('#jabatan').val('');
            } else {
                $('#pamong').val($("#pamong option[data-ttd='1']").val());
                $('#pamong').change();
            }
        }

        function ambil_pamong(elem) {
            var niap = elem.data('niap');
            $('#pamong_niap').val(niap);

            var nip = elem.data('nip');
            $('#pamong_nip').val(nip);

            elem.closest('.box-body').find('select[name=jabatan]').val(elem.data('jabatan'));
            $('#pamong_id').val(elem.data('pamong-id'));
        }
    </script>
@endpush
