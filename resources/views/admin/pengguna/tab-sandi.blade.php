<div class="tab-pane" id="sandi">
    {!! form_open_multipart(ci_route('pengguna.update_password'), 'id="validate_password"') !!}
    <div class="box-body">
        <div class="form-group">
            <label for="pass_lama">Kata Sandi Lama</label>
            <div class="input-group">
                <input id="pass_lama" class="form-control input-sm required" type="password" name="pass_lama" autocomplete="off" />
                <span class="input-group-addon input-sm reveal-lama" id="reveal-lama" data-toggle="tooltip"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
        <div class="form-group">
            <label for="pass_baru">Kata Sandi Baru</label>
            <div class="input-group">
                <input id="pass_baru" class="form-control input-sm required pwdLengthNist" type="password" name="pass_baru" autocomplete="off" />
                <span class="input-group-addon input-sm reveal-baru" data-toggle="tooltip"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
        <div class="form-group">
            <label for="pass_baru1">Konfirmasi Kata Sandi Baru</label>
            <div class="input-group">
                <input id="pass_baru1" class="form-control input-sm required pwdLengthNist" type="password" name="pass_baru1" autocomplete="off" />
                <span class="input-group-addon input-sm reveal-baru1" data-toggle="tooltip"><i class="fa fa-eye-slash"></i></span>
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
        $('document').ready(function () {
            validate("#validate_password");

            setTimeout(function () {
                $('#pass_baru1').rules('add', {
                    equalTo: '#pass_baru'
                })
            }, 500);

            // Set default: semua input password disembunyikan, tombol title = "Tampilkan Sandi"
            $('.input-group-addon').each(function () {
                let $icon = $(this).find('i');
                let $input = $(this).siblings('input');

                $input.attr('type', 'password');
                $icon.removeClass("fa-eye").addClass("fa-eye-slash");
                $(this).attr('title', 'Tampilkan Sandi');
            });

            // Event handler utk toggle sandi
            $('.input-group-addon').on('click', function () {
                let $icon = $(this).find('i');
                let $input = $(this).siblings('input');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass("fa-eye-slash").addClass("fa-eye");
                    $(this).attr('title', 'Sembunyikan Sandi');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass("fa-eye").addClass("fa-eye-slash");
                    $(this).attr('title', 'Tampilkan Sandi');
                }
            });

            $('[data-toggle="tooltip"]').tooltip();
        });


    </script>
@endpush
