<div class="modal fade in" id="profil_pengguna">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"> Pengaturan Pengguna</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-3">
            <div class="box box-primary">
              <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{ AmbilFoto($auth->foto) }}" alt="Foto">
              </div>
            </div>
            @if ($auth->email_verified_at === null)
              {!! form_open(route('user_setting.kirim_verifikasi')) !!}
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-sm btn-warning btn-block"><i class="fa fa-share-square"></i> Verifikasi Email</button>
                </span>
              </form>
            @endif
          </div>
          <div class="col-sm-9">
            <div class="box box-danger">
              {!! form_open_multipart(route('user_setting.update', $auth->id), 'id="validate_user"') !!}
                <div class="box-body">
                  <div class="form-group">
                    <label for="tgl_peristiwa">Username</label>
                    <input class="form-control input-sm" type="text" value="{{ $auth->username }}" autocomplete="off" disabled></input>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control input-sm" type="text" value="{{ $auth->email }}" readonly></input>
                  </div>
                  <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input class="form-control input-sm" type="text" name="nama" value="{{ $auth->nama }}" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="pass_lama">Kata Sandi Lama</label>
                    <input class="form-control input-sm required" type="password" name="pass_lama" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="pass_baru">Kata Sandi Baru</label>
                    <input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru" name="pass_baru" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="pass_baru1">Kata Sandi Baru (Ulangi)</label>
                    <input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru1" name="pass_baru1" autocomplete="off"/>
                  </div>
                  <div class="form-group">
                    <label for="foto">Ganti Foto</label>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control" id="file_path_user" name="foto">
                      <input type="file" class="hidden" id="file_user" name="foto">
                      <input type="hidden" name="old_foto" value="{{ $auth->foto }}">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat" id="file_browser_user"><i class="fa fa-search"></i> Browse</button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                  <button id="btnSubmit" type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
  $('document').ready(function() {

    $("#validate_user").validate();

    setTimeout(function() {
      $('#pass_baru1').rules('add', {
        equalTo: '#pass_baru'
      })
    }, 500);

    $('#file_browser_user').click(function(e) {
      e.preventDefault();
      $('#file_user').click();
    });

    $('#file_user').change(function() {
      $('#file_path_user').val($(this).val());
    });

    $('#file_path_user').click(function() {
      $('#file_browser_user').click();
    });
  });
</script>
@endpush
