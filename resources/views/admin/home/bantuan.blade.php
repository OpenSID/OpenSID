<div class="modal fade in" id="pengaturan-bantuan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Pengaturan Program Bantuan</h4>
      </div>
      {!! form_open(route('setting.new_update'), 'id="main_bantuan"') !!}
        <div class="modal-body">
          <div class="form-group">
            <label>Program Bantuan Untuk Ditampilkan</label>
            <select name="dashboard_program_bantuan" class="form-control input-sm required select2">
              <option value="">Semua Program Bantuan</option>
              @foreach ($bantuan['program'] as $id => $nama)
                <option value="{{ $id }}" {{ selected($id, $setting->dashboard_program_bantuan) }}>{{ $nama }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function() {
    $(".select2").select2();
    $("#main_bantuan").validate();
  })
</script>
@endpush