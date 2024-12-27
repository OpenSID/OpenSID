<form id="validasi" action="{{ $form_action }}" method="post" target="_blank">
    <div class="modal-body">
        @if ($sensor_nik)
            <div class="row">
                <div class="col-sm-12">
                    <label for="nama">Centang kotak berikut apabila NIK/No. KK ingin disensor</label>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="sensor_nik" class="form-check-input" id="privasi_nik">
                            <label class="form-check-label" for="cetak_privasi_nik">Sensor NIK/No. KK</label>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="form-group">
            <label for="pamong_ttd">Laporan Ditandatangani</label>
            <select class="form-control input-sm select2 required" name="pamong_ttd">
                <option value="">Pilih Staf {{ ucwords(setting('sebutan_pemerintah_desa')) }}</option>
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($pamong_ttd['pamong_id'] == $data['pamong_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="pamong_ketahui">Laporan Diketahui</label>
            <select class="form-control input-sm select2 required" name="pamong_ketahui">
                <option value="">Pilih Staf {{ ucwords(setting('sebutan_pemerintah_desa')) }}</option>
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($pamong_ketahui['pamong_id'] == $data['pamong_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> {{ ucwords($aksi) }}</button>
    </div>
</form>
@include('admin.layouts.components.validasi_form')
<script>
    $(document).ready(function() {
        $('.modal:visible').find('form').validate()

        $('#validasi').submit(function() {
            $('#modalBox').modal('hide')
        })
    })
</script>
