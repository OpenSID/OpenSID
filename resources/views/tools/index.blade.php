<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Amankan password database</title>
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
                <i class="fa fa-warning text-danger"></i><b> Amankan password database</b>
            </h4>
            <hr>
            {!! form_open_multipart('', 'class="form-horizontal" id="validasi"') !!}
            <div class="box box-info">
                @if (empty($encryptedPassword))
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label">Password database</label>
                            <div>
                                <input name="password" class="form-control input-sm" maxlength="30" type="text" value="" />
                            </div>
                        </div>
                    </div>
                    <div class='box-footer'>
                        <div>
                            <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Konversi</button>
                        </div>
                    </div>
                @else
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label">Password database</label>
                            <div>
                                <textarea id="password_encrypted" rows="5" class="form-control">{{ $encryptedPassword }}</textarea>
                            </div>
                            <p class="text-helper">
                                Ubah nilai <strong>$db['default']['password']</strong> pada file <strong>{{ LOKASI_CONFIG_DESA . 'database.php' }}</strong> dengan nilai diatas
                            </p>
                        </div>

                    </div>
                    <div class='box-footer'>
                        <div>
                            <button type='button' onclick="copyText()" class='btn btn-social btn-success btn-sm pull-right'><i class='fa fa-copy'></i> Salin teks</button>
                        </div>
                    </div>
                @endif
            </div>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript">
    function copyText() {
        var textToCopy = document.getElementById("password_encrypted");

        var range = document.createRange();
        range.selectNode(textToCopy);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();
        alert("Teks telah disalin!");
    }
</script>

</html>
