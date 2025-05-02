<form id="validasi" action="{{ $formAction }}" method="post" target="_blank">
    <div class="modal-body">
        <input type="hidden" name="params" value="">
        <div class="form-group">
            <label for="pamong_ttd">Laporan Ditandatangani</label>
            <select class="form-control input-sm select2 required" name="pamong_ttd">
                <option value="">Pilih Staf {{ ucwords(setting('sebutan_pemerintah_desa')) }}</option>
                @foreach ($pamong as $data)
                    <option value="{{ $data['pamong_id'] }}" @selected($data['jabatan_id'] == $pamong_ketahui['jabatan_id'])>{{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="laporan_no">Laporan No.</label>
            <input id="laporan_no" class="form-control input-sm required" type="text" placeholder="Laporan No." name="laporan_no" value="">
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-ok">
            @if ($aksi == 'cetak' || $aksi == 'pdf')
                <i class='fa fa-print'></i> Cetak
            @else
                <i class='fa fa-download'></i> Unduh
            @endif
        </button>
    </div>
</form>
@include('admin.layouts.components.validasi_form')
<script type="text/javascript">
    $(function() {
        refreshFormCsrf();
        let _objParams = $('#tabeldata').DataTable().ajax.params()
        delete(_objParams.draw)
        delete(_objParams.search)
        $('input[name=params]').val(JSON.stringify(_objParams))
    });
</script>
