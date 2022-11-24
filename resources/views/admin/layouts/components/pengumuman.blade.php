@push('css')
<style>
  #form-pengumuman .modal-body {
		height: auto;
	}
	#checkbox_pengumuman {
		font-weight: bolder;
		margin-top: 10px;
	}
</style>
@endpush
{!! form_open(null, 'id="form-pengumuman"') !!}
  <input type="hidden" name="notifikasi" value="1">
  <input type="hidden" name="kode" value="{{ $kode }}">
  <input type="hidden" id="jenis" value="{{ $jenis }}">
  <div class="modal fade" id="pengumuman" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header btn-info">
          <h4 class="modal-title" id="myModalLabel">{!! $judul !!}</h4>
        </div>
        <div class="modal-body">
          <div id="isi">

            {!! $isi !!}

            @if ($jenis == 'pengumuman')
              <div class="checkbox">
                <label>
                  <input type="checkbox"  id="checkbox_pengumuman" name="non_aktifkan" value="non_aktifkan"></input>&nbsp;Jangan tampilkan lagi
                </label>
              </div>
            @endif
            
          </div>
          <center>
            <div id="indikator" class="text-center">
              <img src="<?= asset('images/background/loading.gif') ?>">
            </div>
          </center>
        </div>
        <div class="modal-footer" id="m_footer">
          @if ($jenis == 'pengumuman')
            <button type="reset" data-dismiss="modal" id="btnSetuju" class="btn btn-social btn-warning btn-sm"><i class="fa fa-sign-out"></i> OK</button>
          @else
            <button type="reset" data-dismiss="modal" id="btnTidak" class="btn btn-social btn-danger btn-sm"><i class="fa fa-sign-out"></i> Tidak</button>
            <button type="submit" id="btnSetuju" class="btn btn-social btn-warning btn-sm"><i class="fa fa-check"></i> Setuju</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
  $('document').ready(function() {
    $('#pengumuman').modal({backdrop: 'static', keyboard: false});
    $('#indikator').hide();
  });

  $('#btnSetuju').on('click', function() {
    var link = "{{ route($aksi_ya) }}";

    $('#isi').hide();
    $('#m_footer').hide();
    $('#indikator').show();
    $('#btnSetuju').prop('disabled', true);
    $('#btnTidak').prop('disabled', true);
    $.ajax({
      type: "POST",
      url: link,
      data: $('#form-pengumuman').serialize(),
      success: function() {
        $('#indikator').hide();
        $('#pengumuman').modal('hide');
      }
    });
    return false;
  });

  // Persetujuan langsung redirect ke aksi_tidak
  $('#btnTidak').on('click', function() {
    var link = "{{ route($aksi_tidak) }}";

    if ($('#jenis').val() == 'persetujuan') location.href = link;

    $('#isi').hide();
    $('#m_footer').hide();
    $('#indikator').show();
    $('#btnSetuju').prop('disabled', true);
    $('#btnTidak').prop('disabled', true);
    $.ajax({
      type: "POST",
      url: link,
      data: $('#form-pengumuman').serialize(),
      success: function() {
        $('#indikator').hide();
        $('#pengumuman').modal('hide');
      }
    });
    return false;
  });
</script>
@endpush
