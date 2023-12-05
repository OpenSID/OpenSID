<!DOCTYPE html>
<html>
<head>
    <title>Offline Mode - {{ ucwords(setting('sebutan_desa').' '.$desa['nama_desa']) }}</title>
    <link rel="shortcut icon" href="{{ favico_desa() }}"/>
</head>
<body>
    <br/><br/><br/>
    <div align="center">
        <img class="profile-user-img img-responsive img-circle" src="{{ gambar_desa($desa['logo']); }}" alt="Logo">
        <p>
            Selamat datang di Halaman Situs Resmi {{ ucwords(setting('sebutan_desa').' '.$desa['nama_desa']) }}<br/>
            Kami mohon maaf untuk sementara halaman tidak dapat di akses, dikarenakan sedang adanya perbaikan oleh tim terkait.
        </p>
        <p>
            Jika ada keperluan yang mendesak silakan langsung datang ke Kantor {{ ucwords(setting('sebutan_desa')) }}.<br>
            Alamat : {{ $desa['alamat_kantor'] }}<br>
            Email : {{ $desa['email_desa'] }}<br>
            Telepon : {{ $desa['telepon'] }}
        </p>
        <p>
            {{ ucwords($jabatan).' '.$desa['nama_desa'] }}
            <br>
            <br>
            <br>
            <u><b>{{ $nama_kepala_desa }}</b></u><br>
            NIP. {{ $nip_kepala_desa }}
        </p>
    </div>
</body>
</html>