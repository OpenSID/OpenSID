@php $nama_desa = ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) @endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->nama }} - {{ $nama_desa }}
    </title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        iframe {
            border: none;
            width: 100%;
            height: calc(100% - 56px);
            display: none;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
</head>

<body>

    <div class="spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Memuat...</span>
        </div>
    </div>

    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ gambar_desa($desa['logo']) }}" alt="{{ $nama_desa }}">
                <span>
                    {{ $nama_desa }}
                </span>
            </a>
            <button class="btn btn-primary" onclick="window.location.href='{{ ci_route() }}'">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
        </div>
    </nav>

    <iframe id="contentFrame" src="{{ $menu->link }}" scrolling="yes"></iframe>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const iframe = document.getElementById('contentFrame');
        iframe.onload = function() {
            document.querySelector('.spinner').style.display = 'none';
            iframe.style.display = 'block';
        };
    </script>

</body>

</html>
