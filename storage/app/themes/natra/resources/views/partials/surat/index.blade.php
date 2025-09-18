<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ setting('admin_title') . ' ' . ucwords(setting('sebutan_desa')) . ' ' . identitas('nama_desa') . get_dynamic_title_page_from_path() }}
    </title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
</head>

<body class="hold-transition">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Section -->
            <div style="padding: 20px" class="col-md-4">
                <div class="text-center">
                    <img class="logo" src="{{ gambar_desa(identitas('logo')) }}" alt="logo-desa">
                    <h4>
                        <b>
                            Pemerintah {{ ucwords(setting('sebutan_kabupaten') . ' ' . identitas('nama_kabupaten')) }}<br />
                            {{ ucwords(setting('sebutan_kecamatan') . ' ' . identitas('nama_kecamatan')) }}<br />
                            {{ ucwords(setting('sebutan_desa') . ' ' . identitas('nama_desa')) }}
                        </b>
                    </h4>
                    <hr style="border-bottom: 2px solid #000000; height:0px;">
                </div>
                <div id="message"></div>
            </div>

            <div class="col-md-8">
                <iframe id="pdf-viewer" style="width: 100%; height: 100vh" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const messageContainer = document.getElementById('message');
            const pdfViewer = document.getElementById('pdf-viewer');
            const notFoundHTML = `
            <div class="callout callout-danger">
              <h5><b>Surat tidak ditemukan dalam sistem.</b></h5>
            </div>
            `;
            const loadingHTML = `
              <div class="callout callout-info">
                <h5><b>Harap tunggu, sedang memproses data.</b></h5>
              </div>
            `;

            // Set default message
            messageContainer.innerHTML = loadingHTML;

            // Fetch and display data
            fetch("{{ route('api.verifikasi-surat') }}?filter[id]={{ $id }}")
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.data.length > 0) {
                        const surat = data.data[0].attributes;

                        // Update left section
                        const suratHTML = `
              <table>
                <tbody>
                  <tr>
                    <td colspan="3"><u><b>Menyatakan Bahwa :</b></u></td>
                  </tr>
                  <tr>
                    <td width="30%">Nomor Surat</td>
                    <td width="1%">:</td>
                    <td id="nomor_surat">${surat.nomor_surat}</td>
                  </tr>
                  <tr>
                    <td>Tanggal Surat</td>
                    <td>:</td>
                    <td id="tanggal_surat">${surat.tanggal}</td>
                  </tr>
                  <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td id="perihal_surat">Surat ${surat.perihal}</td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td id="penduduk_surat">a/n ${surat.nama_penduduk}</td>
                  </tr>
                  <tr>
                    <td colspan="3"><u><b>Ditandatangani oleh :</b></u></td>
                  </tr>
                  <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td id="pamong_surat">${surat.pamong_nama}</td>
                  </tr>
                  <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td id="jabatan_pamong_surat">${surat.pamong_jabatan}</td>
                  </tr>
                </tbody>
              </table>
              <br />
              <div class="callout callout-success">
                <h5><b>Adalah benar dan tercatat dalam database sistem informasi kami.</b></h5>
              </div>`;
                        messageContainer.innerHTML = suratHTML;

                        // Update PDF viewer
                        if (surat.pdf) {
                            const pdfData = `data:application/pdf;base64,${surat.pdf}`;
                            pdfViewer.src = pdfData;
                        } else {
                            pdfViewer.src = '';
                            console.warn('No PDF data available.');
                            messageContainer.innerHTML = notFoundHTML;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching surat:', error);
                    messageContainer.innerHTML = notFoundHTML;
                });
        });
    </script>
</body>

</html>
