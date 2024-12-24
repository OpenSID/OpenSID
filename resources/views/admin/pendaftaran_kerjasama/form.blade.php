<section class="content-header">
    <h1>Pendaftaran Kerjasama</h1>
    <ol class="breadcrumb">
        <li><a href="{{ site_url('beranda') }}"><i class="fa fa-home"></i> Beranda</a></li>
        <li class="active">Pendaftaran Kerjasama</li>
    </ol>
</section>
<section class="content" id="maincontent">
    @if (!cek_koneksi_internet())
        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="icon fa fa-ban"></i>
                <h3 class="box-title">Tidak Terhubung Dengan Jaringan</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-danger">
                    <h5>Data Gagal Dimuat, Harap Periksa Jaringan Anda Telebih Dahulu.</h5>
                </div>
            </div>
        </div>
    @else
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Pendaftaran Kerjasama {{ config_item('nama_lembaga') }}</h3>
            </div>
            <div class="box-body">
                <p>{{ config_item('nama_lembaga') }} (lembaga hukum dikukuhkan Keputusan Menteri Hukum dan Hak Asasi Manusia
                    Nomor AHU-0001417.AH.01.08.Tahun 2021) menyediakan aplikasi dan layanan yang memerlukan kontribusi yang
                    perlu dianggarkan Desa. Untuk memenuhi peraturan pengadaan yang berlaku, Desa perlu memiliki kerjasama
                    pengadaan dengan {{ config_item('nama_lembaga') }} sebelum dapat menggunakan aplikasi dan layanan {{ config_item('nama_lembaga') }} berbayar tersebut.</p>
                <p>Gunakan fitur ini untuk mendaftarkan dan mengeksekusi kerjasama resmi dengan {{ config_item('nama_lembaga') }}. Setelah Kesepakatan Kerjasama antara Desa dan {{ config_item('nama_lembaga') }} berlaku, Desa akan terdaftar sebagai Desa Digital {{ config_item('nama_lembaga') }} dan
                    berhak mengakses aplikasi dan layanan {{ config_item('nama_lembaga') }} berbayar dan program-program peningkatan desa digital lainnya.</p>
                <p>Cetak dokumen Kesepakatan Kerjasama menggunakan tombol yang disediakan. Langkah untuk melengkapi
                    pendaftaran adalah sebagai berikut:</p>
                <p>
                <ol>
                    <li>Cetak dokumen Kesepakatan Kerjasama (Pada pengaturan cetak, Option : Headers and Footers jangan di
                        centang).</li>
                    <li>Isi tanggal penandatanganan.</li>
                    <li>Tandatangani oleh Kades sebagai PIHAK KESATU di atas meterai Rp10.000</li>
                    <li>Scan dokumen yang telah ditandatangani.</li>
                    <li>Unggah hasil scan menggunakan form pendaftaran.</li>
                    <li>Simpan dokumen asli di arsip kantor desa.</li>
                    <li>Cek email inbox/pesan yang Anda gunakan untuk memverifikasi.</li>
                    <li>Setelah pendaftaran diverifikasi dan kerjasama diaktifkan oleh {{ config_item('nama_lembaga') }},
                        email pemberitahuan akan dikirim ke alamat email terdaftar.</li>
                </ol>
            </div>
        </div>
        @if ($response->data->status_langganan === 'menunggu verifikasi email')
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="icon fa fa-info"></i>
                    <h3 class="box-title">Status Registrasi</h3>
                </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        <h5>Kami telah mengirim link verifikasi ke {{ $response->data->email }} <br> Silahkan cek email Anda
                            untuk memverifikasi, atau kirim ulang pendaftaran kerjasama menggunakan email aktif untuk menerima
                            link verifikasi baru.</h5>
                    </div>
                </div>
            </div>
        @elseif ($response->data->status_langganan === 'menunggu verifikasi pendaftaran')
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="icon fa fa-info"></i>
                    <h3 class="box-title">Status Registrasi</h3>
                </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        <h5>Dokumen permohonan kerjasama Desa anda sedang diperiksa oleh Pelaksana Layanan {{ config_item('nama_lembaga') }}.</h5>
                    </div>
                </div>
            </div>
        @endif
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="icon fa fa-info"></i>
                <h3 class="box-title">Langkah-langkah melakukan pengecekan email untuk verifikasi
            </div>
            <div class="box-body">
                <div class="callout callout-info">
                    <h5>1. Cek folder kotak masuk / inbox, jika ada, maka silahkan klik pesan tersebut lalu klik tombol
                        verifikasi email. </h5>
                    <h5>2. Cek folder spam, jika ada, maka:<br>
                        - Klik pesan lalu hapus label spam pada pesan tersebut.<br>
                        - Setelah label spam dihapus, pesan akan masuk ke folder inbox.<br>
                        - Selanjutnya cek folder inbox, dan silahkan klik pesan dan klik tombol verifikasi.<br>
                    </h5>
                    <h5>3. Jika Anda tidak menerima pesan pada folder inbox dan folder spam, silahkan kirim ulang
                        pendaftaran kerjasama menggunakan email aktif untuk menerima link verifikasi baru, pastikan email
                        sudah benar.</h5>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Form Pendaftaran Kerjasama</h3>
            </div>
            <form id="validasi" action="{{ site_url('pendaftaran_kerjasama/register') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="{{ $response->data->user_id ?? 0 }}">
                        <input type="hidden" name="status_langganan_id" value="{{ $status = $response->data->status_langganan_id ?? 4 }}">
                        <label class="col-sm-3 control-label" for="email">Email</label>
                        <div class="col-sm-8">
                            @if ($response->data->status_langganan === 'menunggu verifikasi email')
                                <input id="email" class="form-control input-sm required" type="text" placeholder="Gunakan email yang valid" name="email" value="">
                            @else
                                <input id="email" class="form-control input-sm required" type="text" placeholder="Gunakan email yang valid" name="email" value="{{ $response->data->email }}">
                            @endif
                            @if ($email = session('email'))
                                <p class="error">{{ $email }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Registrasi</label>
                        <div class="col-sm-8">
                            <input class="form-control input-sm" type="text" name="status_registrasi" value="{{ $response->data->status_langganan ?? 'belum terdaftar' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="email">Kode {{ ucfirst(setting('sebutan_desa')) }}</label>
                        <div class="col-sm-8">
                            <input class="form-control input-sm bilangan_titik required" type="text" name="desa" value="{{ $response->data->desa_id ?? kode_wilayah($desa['kode_desa']) }}">
                            @if ($desa = session('desa'))
                                <p class="error">{{ $desa }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="domain">Domain {{ ucfirst(setting('sebutan_desa')) }}</label>
                        <div class="col-sm-8">
                            <input id="domain" class="form-control input-sm" type="text" readonly name="domain" value="{{ $response->data->domain ?? APP_URL }}">
                            @if ($domain = session('domain'))
                                <p class="error">{{ $domain }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="kontak_nama">Nama Kontak</label>
                        <div class="col-sm-8">
                            <input id="kontak_nama" class="form-control input-sm nama" type="text" name="kontak_nama" value="{{ $response->data->nama_kontak }}">
                            @if ($kontak_nama = session('kontak_nama'))
                                <p class="error">{{ $kontak_nama }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="email">No HP. Kontak</label>
                        <div class="col-sm-8">
                            <input id="kontak_no_hp" class="form-control input-sm" type="number" name="kontak_no_hp" value="{{ $response->data->no_hp_kontak }}">
                            @if ($kontak_no_hp = session('kontak_no_hp'))
                                <p class="error">{{ $kontak_no_hp }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="upload">Unggah Dokumen Yang Telah Ditandatangani
                            <code>(format .pdf)</code></label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path" name="permohonan">
                                <input id="file" type="file" class="hidden" accept=".pdf" name="permohonan">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Pilih</button>
                                </span>
                                <span class="input-group-btn">
                                    <a target="_blank" href="<?= site_url('pendaftaran_kerjasama/dokumen_template') ?>" type="button" class="btn btn-success"><i class="fa fa-download"></i> Unduh Dokumen
                                        Kerjasama</a>
                                </span>
                            </div>
                            @if ($permohonan = session('permohonan'))
                                <p class="error">{{ $permohonan }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    @if (can('u'))
                        <button type="button" class="simpan btn btn-social btn-info btn-sm pull-right" {{ in_array($status, [5, 6]) ? 'disabled' : '' }}><i class="fa fa-check"></i> Simpan</button>
                    @endif
                </div>
            </form>
        </div>
    @endif
</section>
