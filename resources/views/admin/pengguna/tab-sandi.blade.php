<div class="tab-pane" id="sandi">
    {!! form_open_multipart(ci_route('pengguna.update_password'), 'id="validate_password"') !!}
    <div class="box-body">
        <div class="form-group">
            <label for="pass_lama">Kata Sandi Lama</label>
            <div class="input-group">
                <input id="pass_lama" class="form-control input-sm required" type="password" name="pass_lama" autocomplete="off" />
                <span class="input-group-addon input-sm reveal-lama" id="reveal-lama"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
        <div class="form-group">
            <label for="pass_baru">Kata Sandi Baru</label>
            <div class="input-group">
                <input id="pass_baru" class="form-control input-sm required pwdLengthNist" type="password" name="pass_baru" autocomplete="off" />
                <span class="input-group-addon input-sm reveal-baru"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
        <div class="form-group">
            <label for="pass_baru1">Kata Sandi Baru (Ulangi)</label>
            <div class="input-group">
                <input id="pass_baru1" class="form-control input-sm required pwdLengthNist" type="password" name="pass_baru1" autocomplete="off" />
                <span class="input-group-addon input-sm reveal-baru1"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
            Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
            Simpan</button>
    </div>
    </form>
</div>
@push('scripts')
    <script>
        $('document').ready(function() {
            validate("#validate_password");

            setTimeout(function() {
                $('#pass_baru1').rules('add', {
                    equalTo: '#pass_baru'
                })
            }, 500);

            $('.reveal-lama').on('click', function() {
                var $pwd = $("#pass_lama");
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');

                    $(".reveal-lama i").removeClass("fa-eye-slash");
                    $(".reveal-lama i").addClass("fa-eye");
                } else {
                    $pwd.attr('type', 'password');

                    $(".reveal-lama i").addClass("fa-eye-slash");
                    $(".reveal-lama i").removeClass("fa-eye");
                }
            });

            $('.reveal-baru').on('click', function() {
                var $pwd = $("#pass_baru");
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');

                    $(".reveal-baru i").removeClass("fa-eye-slash");
                    $(".reveal-baru i").addClass("fa-eye");
                } else {
                    $pwd.attr('type', 'password');

                    $(".reveal-baru i").addClass("fa-eye-slash");
                    $(".reveal-baru i").removeClass("fa-eye");
                }
            });

            $('.reveal-baru1').on('click', function() {
                var $pwd = $("#pass_baru1");
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');

                    $(".reveal-baru1 i").removeClass("fa-eye-slash");
                    $(".reveal-baru1 i").addClass("fa-eye");
                } else {
                    $pwd.attr('type', 'password');

                    $(".reveal-baru1 i").addClass("fa-eye-slash");
                    $(".reveal-baru1 i").removeClass("fa-eye");
                }
            });
        });
    </script>
@endpush
