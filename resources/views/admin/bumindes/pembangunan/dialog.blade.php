<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">

<form id="validasi" action="{{ $formAction }}" method="post" target="_blank">
    <div class="modal-body">
        @if ($tahun && $tahun->count() > 0)
            <div class="form-group">
                <label class="control-label">Tahun Anggaran</label>
                <select class="form-control input-sm jenis_link select2" name="tahun">>
                    <option value="">Semua Tahun</option>
                    @foreach ($tahun as $item)
                        <option>{{ $item->tahun_anggaran }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="form-group">
            <label class="control-label">Tanggal Cetak</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right required" id="tgl_1" name="tgl_cetak" type="text" value="{{ date('d-m-Y') }}">
            </div>
        </div>
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
<!-- moment js -->
<script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
<!-- bootstrap Date time picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/id.js') }}"></script>
<!-- bootstrap Date picker -->
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap-datepicker.id.min.js') }}"></script>
<!-- Script-->
<script src="{{ asset('js/custom-datetimepicker.js') }}"></script>
<script type="text/javascript">
    $('document').ready(function() {
        $('#validasi').submit(function() {
            if ($('#validasi').valid()) {
                $('#modalBox').modal('hide');
            }
        });
    });
</script>
