<div class="form-group subtitle_head">
    <label class="col-sm-12 control-label">SIFAT SURAT</label>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Karakter Surat</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm select2" id="karakter" name="karakter">
            @foreach ($karakter_surat as $key => $data)
                <option value="{{ $key }}">{{ $data }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Derajat Surat</label>
    <div class="col-sm-6 col-lg-4">
        <select class="form-control input-sm select2" id="derajat" name="derajat">
            @foreach ($derajat_surat as $key => $data)
                <option value="{{ $key }}">{{ $data }}</option>
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
            if (atas_nama.includes('a.n')) {
                ub = $("#pamong option[data-ttd='1']").val();

                if (ub) {
                    $('#pamong').val(ub);
                } else {
                    $('#pamong').val('');
                }
                $('#pamong').attr('disabled', true);
            } else if (atas_nama.includes('u.b')) {
                $('#pamong').val('');
                $("#pamong option[data-jenis='1']").hide();
                $("#pamong option[data-ttd='1']").hide();
                $('#pamong').attr('disabled', false);
            } else {
                $('#pamong').val($("#pamong option[data-jenis='1']").val());
                $('#pamong').attr('disabled', true);
            }

            $('#pamong').change();
        }
    </script>
@endpush
