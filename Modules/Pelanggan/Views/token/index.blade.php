<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Perbarui Token Pelanggan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}" />
    <style type="text/css">
        body {
            overflow: hidden;
        }

        .red {
            color: red;
        }

        .login-box-msg {
            padding: 10px 0;
        }

        .register-box-body {
            padding: 20px;
            border-radius: 5px;
        }

        @media (min-device-width : 1024px) {
            .register-box {
                width: 650px;
            }
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-box-body">
            <h4 class="login-box-msg">
                <i class="fa fa-warning text-danger"></i><b> Token pelanggan tidak ditemukan</b>
            </h4>
            <hr>
            <div>
                {!! form_open(ci_route('token.update'), 'class="form-horizontal" id="validasi"') !!}
                <div class="form-group <?= $ci->session->has_userdata('error') ? 'has-error' : '' ?>">
                    <textarea
                        name="token"
                        rows="5"
                        id="token"
                        autocomplete="off"
                        placeholder="Masukkan token pelanggan"
                        class="form-control required"
                        required
                    ></textarea>
                    <?php if ($ci->session->has_userdata('error')) : ?>
                    <span class="help-block"><?= $ci->session->error ?></span>
                    <?php endif ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block btn-flat">Perbarui</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
