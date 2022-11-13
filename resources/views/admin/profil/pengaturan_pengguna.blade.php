<div class="modal fade in" id="profil_pengguna">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Pengaturan Pengguna</h4>
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
              <div class="row">
                <div class="col-sm-12">
                    {!! form_open(route('user_setting.kirim_verifikasi')) !!}
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-warning btn-block"><i class="fa fa-share-square"></i> Verifikasi Email</button>
                      </span>
                    </form>
                </div>
              </div>
            @endif
            
            @if ($auth->telegram_verified_at === null && setting('telegram_token') != null)
            <div class="row">
              <div class="col-sm-12">
                  <span class="input-group-btn" style="padding-top: 0.5rem;">
                    <button type="button" id="verif_telegram" class="btn btn-sm btn-warning btn-flat btn-block"><i class="fa fa-share-square"></i> Verifikasi Telegram</button>
                  </span>
              </div>
            </div>
            @endif
          </div>
          <div class="col-md-9 col-sm-12">
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
                  <div class="form-group">
                    <label for="notif_telegram" class="control-label">Notifikasi Telegram</label>
                    <div class="btn-group col-xs-12 col-sm-8 input-group" data-toggle="buttons">
                      <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($auth->notif_telegram == 1) @disabled(setting('telegram_token') == null || $auth->telegram_verified_at == null)" >
                        <input type="radio" name="notif_telegram" class="form-check-input" value="1" autocomplete="off" @selected($auth->notif_telegram == 1) @disabled(setting('telegram_token') == null || $auth->telegram_verified_at == null)> Aktif
                      </label>
                      <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($auth->notif_telegram == 0)  @disabled(setting('telegram_token') == null || $auth->telegram_verified_at == null)">
                        <input type="radio" name="notif_telegram" class="form-check-input" value="0" autocomplete="off" @selected($auth->notif_telegram == 0) @disabled(setting('telegram_token') == null || $auth->telegram_verified_at == null)> Matikan
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="id_telegram">User ID Telegram</label>
                    <input class="form-control input-sm pwdLengthNist" type="text" id="id_telegram" name="id_telegram" value="{{ $auth->id_telegram }}" @disabled(setting('telegram_token') == null) />
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
@include('admin.layouts.components.sweetalert2')
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

    $('#verif_telegram').click(function() {
      Swal.fire({title: 'Mengirim OTP', allowOutsideClick: false, allowEscapeKey:false, showConfirmButton:false, didOpen: () => {Swal.showLoading()}});
      $.ajax({
        url: '{{ route("user_setting.kirim_otp_telegram") }}',
        type: 'Post',
        data: {
          'sidcsrf' : getCsrfToken(),
          'id_telegram' : $('#id_telegram').val()
        },
      })
      .done(function(response) {
        if (response.status == true) {
          Swal.fire({
            title: 'Masukan Kode OTP',
            input: 'text',
            inputPlaceholder : 'Masukan Kode OTP',
            inputValidator: (value) => {
              if (isNaN(value)) {
                return 'Kode OTP harus berupa angka'
              }
            },
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Tutup',
            showLoaderOnConfirm: true,
            preConfirm: (otp) => {
              const formData = new FormData();
              formData.append('sidcsrf', getCsrfToken());
              formData.append('id_telegram', response.data);
              formData.append('otp', otp);

              return fetch(`{{ route("user_setting.verifikasi_telegram") }}`, {
                      method: 'POST',
                      body: formData,
              }).then(response => {
                  if (!response.ok) {
                      throw new Error(response.statusText)
                  }
                  return response.json()
              })
              .catch(error => {
                  Swal.showValidationMessage(
                    `Request failed: ${error}`
                  )
              })
            }
          }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value.status == true) {
                      $('.close').trigger("click"); //close modal
                      Swal.fire({
                  icon: 'success',
                  title: result.value.message,
                  showConfirmButton: false,
                  timer: 1500
                })
                    } else {
                        Swal.fire({ icon: 'error', title: result.value.message })
                    }
                }
          })
        }else{
          Swal.fire({
            icon: 'error',
            text: response.messages,
          })
        }
      })
      .fail(function(e) {
        Swal.fire({
          icon: 'error',
          text: e.statusText,
        })
      });
    });

    $('#id_telegram').change(function(event) {
      $('input[name="telegram_verified_at"]').val('')
    });
  });
</script>
@endpush
