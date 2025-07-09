<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        {{ setting('admin_title') . ' ' . ucwords(setting('sebutan_desa')) . ' ' . identitas('nama_desa') . get_dynamic_title_page_from_path() }}
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
</head>

<body class="hold-transition verifikasi-page">
    <div class="verifikasi-box">
        <div class="verifikasi-box-body">
            <center>
                <img class="logo" src="{{ gambar_desa(identitas('logo')) }}" alt="logo-desa">
                <h4>
                    <b>
                        Pemerintah {{ ucwords(setting('sebutan_kabupaten') . ' ' . identitas('nama_kabupaten')) }}<br />
                        {{ ucwords(setting('sebutan_kecamatan') . ' ' . identitas('nama_kecamatan')) }}<br />
                        {{ ucwords(setting('sebutan_desa') . ' ' . identitas('nama_desa')) }}
                    </b>
                </h4>
                <hr style="border-bottom: 2px solid #000000; height:0px;">
                <div id="message"></div>
            </center>
        </div>
    </div>
</body>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        let _html = `<div class="callout callout-danger">
                      <h5><b>Surat tidak ditemukan dalam sistem.</b></h5>
                    </div>`
        document.getElementById('message').innerHTML = _html
        fetch("{{ route('api.verifikasi-surat') }}?filter[id]={{ $id }}", {
            method: 'get',
        }).then(response => {
            if (response.ok) {
                return response.json();
            }
        }).then(response => {
            if (response.data.length) {

                const _surat = response.data[0].attributes
                _html = `
                  <table>
                    <tbody>
                        <tr>
                          <td colspan="3"><u><b>Menyatakan Bahwa :</b></u></td>
                        </tr>
                        <tr>
                          <td width="30%">Nomor Surat</td>
                          <td width="1%">:</td>
                          <td id="nomor_surat">${_surat.nomor_surat}</td>
                        </tr>
                        <tr>
                          <td>Tanggal Surat</td>
                          <td>:</td>
                          <td id="tanggal_surat">${_surat.tanggal}</td>
                        </tr>
                        <tr>
                          <td>Perihal</td>
                          <td>:</td>
                          <td id="perihal_surat">Surat ${_surat.perihal}</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td id="penduduk_surat">a/n ${_surat.nama_penduduk}</td>
                        </tr>
                        <tr>
                          <td colspan="3"><u><b>Ditandatangani oleh :</b></u></td>
                        </tr>
                        <tr>
                          <td>Nama</td>
                          <td>:</td>
                          <td id="pamong_surat">${_surat.pamong_nama}</td>
                        </tr>
                        <tr>
                          <td>Jabatan</td>
                          <td>:</td>
                          <td id="jabatan_pamong_surat">${_surat.pamong_jabatan}</td>
                        </tr>
                      </tbody>
                    </table>
                    <br />
                    <div class="callout callout-success">
                      <h5><b>Adalah benar dan tercatat dalam database sistem informasi kami.</b></h5>
                    </div>`
                document.getElementById('message').innerHTML = _html
            }

        }).catch(error => {
            document.getElementById('message').innerHTML = _html
        })
    });
</script>

</html>
