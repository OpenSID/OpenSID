<form id="validasi" action="{{ $form_action }}" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Tahun</label>
            <select class="form-control input-sm jenis_link select2" name="tahun">>
                <option value="">Semua</option>
                @foreach ($tahun_laporan as $tahun)
                    <option value="{{ $tahun['tahun'] }}">{{ $tahun['tahun'] }}</option>
                @endforeach
            </select>
        </div>
        @if (isset($kat) && $kat == 3)
            <div class="form-group">
                <label class="control-label">Jenis Peraturan</label>
                <select class="form-control input-sm select2" name="jenis_peraturan" style="width: 100%;">
                    <option value=''>-- Pilih Jenis Peraturan --</option>
                    @foreach ($jenis_peraturan as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if ($pamong)
            <div class="form-group">
                <label class="control-label">Pamong Tertanda</label>
                <select class="form-control input-sm jenis_link select2 required" name="pamong_ttd">
                    <option value="">Pilih Staf Penandatangan</option>
                    @foreach ($pamong as $data)
                        <option value="{{ $data['pamong_id'] }}" @selected($pamong_ttd['pamong_id'] == $data['pamong_id'])>
                            {{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Pamong Mengetahui</label>
                <select class="form-control input-sm jenis_link select2 required" name="pamong_ketahui">
                    <option value="">Pilih Staf Mengetahui</option>
                    @foreach ($pamong as $data)
                        <option value="{{ $data['pamong_id'] }}" @selected($pamong_ketahui['pamong_id'] == $data['pamong_id'])>
                            {{ $data['pamong_nama'] }} ({{ $data['pamong_jabatan'] }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-ok">
            @if ($aksi == 'cetak')
                <i class='fa fa-print'></i> Cetak
            @else
                <i class='fa fa-download'></i> Unduh
            @endif
        </button>
    </div>
</form>
@include('admin.layouts.components.validasi_form')
<script type="text/javascript">
    $('document').ready(function() {
        $('#validasi').submit(function() {
            if ($('#validasi').valid()) {
                $('#modalBox').modal('hide');
            }
        });
    });
</script>
