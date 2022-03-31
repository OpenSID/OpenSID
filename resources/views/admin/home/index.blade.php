@extends('admin.layouts.index')

@push('css')
<style>
  .catatan-scroll {
    height: 400px;
    overflow-y: scroll;
  }
</style>
@endpush

@section('title')
<h1>
  Tentang OpenSID
</h1>
@endsection

@section('breadcrumb')
<li class="active">Tentang OpenSID</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

@include('admin.home.rilis')

@include('admin.home.bantuan')

<div class="row">
  <div class="col-md-6">
    <div class="box box-info">
        <div class="box-body">
          <div class="row">
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3>{{ $dusun }}</h3>
                  <p>Wilayah {{ ucwords($setting->sebutan_dusun) }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-location"></i>
                </div>
                <a href="{{ route('sid_core') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ $penduduk }}</h3>
                  <p>Penduduk</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person"></i>
                </div>
                <a href="{{ route('penduduk.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ $keluarga }}</h3>
                  <p>Keluarga</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-people"></i>
                </div>
                <a href="{{ route('keluarga.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3>{{ $surat }}</h3>
                  <p>Surat Tercetak</p>
                </div>
                <div class="icon">
                  <i class="ion-ios-paper"></i>
                </div>
                <a href="{{ route('keluar.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ $kelompok }}</h3>
                  <p>Kelompok</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-people"></i>
                </div>
                <a href="{{ route('kelompok.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3>{{ $rtm }}</h3>
                  <p>Rumah Tangga</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-home"></i>
                </div>
                <a href="{{ route('rtm.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{ $bantuan['jumlah'] }}</h3>
                  <p>{{ $bantuan['nama'] }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-pie"></i>
                </div>
                <div class="small-box-footer">
                  <a href="#" class="inner text-white rilis_pengaturan" data-remote="false" data-toggle="modal" data-target="#pengaturan-bantuan"><i class="fa fa-gear"></i></a>
                  <a href="{{ route($bantuan['link_detail']) }}" class="inner text-white">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-xs-6">
              <div class="small-box" style="background-color: #39CCCC;">
                <div class="inner">
                  <h3>{{ $pendaftaran }}</h3>
                  <p>Verifikasi Layanan Mandiri</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person"></i>
                </div>
                <a href="{{ route('mandiri') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        
        </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><strong>Anda sedang menggunakan aplikasi OpenSID <?= AmbilVersi()?></strong></h3>
      </div>
      <div class="box-body">
      <div class="box-group" id="accordion">
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Aplikasi OpenSID</a>
            </h4>
          </div>
          <div id="collapse1" class="panel-collapse collapse in">
            <div class="box-body">
              <p>OpenSID adalah aplikasi Sistem Informasi Desa (SID) yang dikembangkan sejak Mei 2016. OpenSID dirancang dan dikelola supaya terbuka dan dapat dikembangkan bersama-sama oleh komunitas peduli SID. Informasi lebih lanjut dapat dilihat di <a href= https://github.com/OpenSID/opensid>https://github.com/OpenSID/OpenSID</a>.</p>
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#catatan-rilis">Catatan Rilis</a>
            </h4>
          </div>
          <div id="catatan-rilis" class="panel-collapse collapse">
            <div class="box-body">
              <div class="catatan-scroll">
                <?= $catatan_rilis ?>
              </div>
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Apakah SID ?</a>
            </h4>
          </div>
          <div id="collapse2" class="panel-collapse collapse">
            <div class="box-body">
              <p>Aplikasi Sistem Informasi Desa (SID) adalah sistem olah data dan informasi berbasis komputer yang dapat dikelola oleh pemerintah dan komunitas desa dalam dua ranah:</p>
              <dl>
                <dt>1. Offline</dt>
                <dd>Aplikasi diinstall dalam komputer server di kantor desa dan dioperasikan sebagai server (pusat data) yang bersifat lokal. Karena tidak terhubung ke internet, SID offline hanya bisa diakses dalam jaringan lokal. Sistem offline ini direkomendasikan untuk diterapkan dalam penggunaan aplikasi SID harian. Database dari hasil proses olah data secara offline itu dapat diunggah ke sistem online secara berkala.</dd>						<dt>2. Online</dt>
                <dd>SID akan optimal jika terhubung ke internet sebagai sistem online berbasis web. SID online akan otomatis berfungsi juga sebagai website desa. Website desa ini memiliki fungsi yang terbagi dalam dua bagian, yakni bagian depan yang bisa diakses oleh publik dan bagian dalam yang hanya bisa diakses oleh administrator sistem.</dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Manajemen Akses SID</a>
            </h4>
          </div>
          <div id="collapse3" class="panel-collapse collapse">
            <div class="box-body">
              <p>Aplikasi SID dirancang untuk mengelola data dasar desa dan informasi desa. Data dasar yang dikelola meliputi data dasar kependudukan dan data dasar aset/sumber daya desa. Data dasar ini menjadi tanggung jawab pemerintah desa dalam pengelolaannya. Hanya pengguna (user) dari pemerintah desa dan tim yang dikoordinasikan oleh pemerintah desa saja yang akan memiliki kewenangan dan hak akses ke dalam sistem. Sementara, user di luar pemerintah desa hanya akan memiliki akses terbatas pada fungsi olah informasi untuk website desa.</p>
              <p>Tingkat user (pengguna) dalam SID:</p>
              <p>
                <ol>
                  <li>
                    Administrator : adalah orang/tim yang bertanggung jawab penuh atas olah data dan informasi dalam SID dan website desa. Orang/tim ini ditunjuk oleh pemerintah desa disahkan dengan surat keputusan kepala desa.
                    <ol>
                      <li>Peran olah data : entry, edit, delete data dasar</li>
                      <li>Peran olah informasi : tulis, edit, publish artikel website</li>
                    </ol>
                  </li>
                  <li>
                    Operator: adalah orang/tim yang bertugas membantu administrator mengelola data dan informasi, tetapi dengan kewenangan yang lebih terbatas.
                    <ol>
                      <li>Peran olah data : entry, edit data dasar</li>
                      <li>Peran olah informasi : tulis, edit artikel website.</li>
                    </ol>
                  </li>
                  <li>
                    Redaksi: adalah orang/tim yang bertugas sebagai redaksi media website desa dan hanya dapat melakukan olah informasi berupa artikel website.
                    <ol>
                      <li>Peran olah informasi : tulis, edit artikel. Redaksi boleh mengubah semua artikel, termasuk menjadikan headline, aktif/non-aktifkan, masukkan ke slider, dsbnya</li>
                    </ol>
                  </li>
                  <li>
                    Kontributor: adalah orang/tim yang bertugas menulis artikel untuk disetujui redaksi untuk ditampilkan di website desa.
                    <ol>
                      <li>Peran olah informasi : tulis, edit artikel yang dibuatnya sendiri. Kontributor tidak dapat menjadikan artikel manapun menjadi headline, aktif/non-aktifkan atau memasukkan ke slider.</li>
                    </ol>
                  </li>
                </ol>
              </p>
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Tahapan Membangun SID</a>
            </h4>
          </div>
          <div id="collapse4" class="panel-collapse collapse">
            <div class="box-body">
              <P>Bagaimana memulai membangun Sistem Informasi Desa (SID) di desa kita? Caranya sangat mudah, tetapi pasti perlu proses yang harus sabar dan cermat untuk dijalani. Siapa pun Anda, baik perorangan maupun mewakili organisasi/lembaga, dapat mencoba mulai membangun SID di desa masing-masing mengikuti langkah-langkah berikut.</P>
              <ol>
                <li>Bentuk tim kerja bersama pemerintah desa</li>
                <li>Diskusikan basis data apa saja yang diperlukan untuk warga</li>
                <li>Himpun data kependudukan warga dari Kartu Keluarga (KK)</li>
                <li>Dapatkan aplikasi SID di <a href= https://github.com/OpenSID/OpenSID/releases>https://github.com/OpenSID/OpenSID/releases</a></li>
                <li>Pasang aplikasi SID di komputer desa</li>
                <li>Masukkan data penduduk ke SID</li>
                <li>Basis data kependudukan sudah bisa dimanfaatkan</li>
                <li>Diskusikan rencana pengembangan SID sesuai kebutuhan desa</li>
                <li>Sebarluaskan informasi desa melalui beragam media untuk warga</li>
              </ol>
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Hak Cipta, Syarat, Dan Ketentuan</a>
            </h4>
          </div>
          <div id="collapse5" class="panel-collapse collapse">
            <div class="box-body">
              <p>Aplikasi Sistem Informasi Desa (SID) dibangun dan dikembangkan pada awalnya oleh COMBINE Resource Institution sejak tahun 2009. Sistem ini dikelola dengan merujuk pada lisensi GNU General Public License Version 3.</p>
              <p>Dengan lisensi GPL v3, semua ubahan OpenSID juga berlisensi GPL v3, yaitu bersifat sumber terbuka.<p>
              <p>OpenSID dikembangkan sejak Mei 2016, dan bebas untuk dimanfaatkan dan dikembangkan oleh semua desa.</p>
              <p>Sejak Januari 2019, OpenSID dikelola oleh Perkumpulan Desa Digital Terbuka (OpenDesa). OpenDesa adalah pemegang hak cipta utama OpenSID.
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">Kontak Dan Informasi</a>
            </h4>
          </div>
          <div id="collapse6" class="panel-collapse collapse">
            <div class="box-body">
              <ol>
                <li>Website Resmi OpenDesa, pengelola OpenSID: </li>
                <a href="http://opendesa.id" target="_blank">http://opendesa.id</a>
                <li>Website Resmi OpenSID: </li>
                <a href="http://opensid.my.id" target="_blank">Website Resmi OpenSID</a>
                <li>Grup Facebook, tempat mendapatkan bantuan dan berbagi pengalaman: </li>
                <a href="https://www.facebook.com/groups/opensid" target="_blank">Forum Pengguna dan Pegiat OpenSID</a>
                <li>Tempat mengunduh rilis OpenSID:</li>
                <a href="https://github.com/OpenSID/OpenSID/releases" target="_blank">https://github.com/OpenSID/OpenSID/releases</a>
                <li>Panduan OpenSID:</li>
                <a href="https://github.com/OpenSID/OpenSID/wiki" target="_blank">https://github.com/OpenSID/OpenSID/wiki</a>
                <li>Channel Youtube OpenSID: </li>
                <a href="https://www.youtube.com/channel/UCvZuSYtrWYuE8otM4SsdT0Q" target="_blank">Kumpulan tutorial video OpenSID</a>
                <li>Forum OpenDesa:</li>
                <a href="https://forum.opendesa.id" target="_blank">https://forum.opendesa.id</a>
                <li>Tempat mendaftarkan masalah dan usulan fitur:</li>
                <a href="https://github.com/OpenSID/OpenSID/issues" target="_blank" sclass="text-green">https://github.com/OpenSID/OpenSID/issues</a>
                <li>Forum diskusi teknis pengembangan OpenSID:</li>
                <a href="https://opensid.slack.com" target="_blank">https://opensid.slack.com</a>
              </ol>
            </div>
          </div>
        </div>
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#donasi">Donasi</a>
            </h4>
          </div>
          <div id="donasi" class="panel-collapse collapse">
            <div class="box-body">
              <h4>OpenSID SELALU GRATIS</h4>
              <div>
                <p>OpenSID selalu gratis dan bebas digunakan dan dikembangkan desa. OpenSID berlisensi GNU General Public License Version 3, yaitu Open Source, di mana scriptnya selalu bebas diperoleh dan disesuaikan desa.</p>
              </div>
              <h4>OpenSID DIKEMBANGKAN KOMUNITAS</h4>
              <div>
                <p>OpenSID dikembangkan oleh komunitas relawan yang peduli dan meluangkan waktu dan keahlian mereka secara sukarela untuk terus mengembangkan OpenSID.</p>
                <p>Selain menyumbangkan waktu mereka, ada kalanya relawan OpenSID juga mengeluarkan dana pribadi untuk mendukung kegiatan OpenSID.</p>
              </div>
              <h4>OpenSID MEMERLUKAN BANTUAN</h4>
              <div>
                <p>Untuk terus berkembang, OpenSID memerlukan bantuan komunitas SID, termasuk donasi. Semua donasi bersifat sukarela dan sama sekali tidak ada keharusan. </p>
                <p>Donasi anda akan memungkinkan OpenSID dikembangkan terus secara berkesinambungan, supaya bisa terus disempurnakan dan bisa dimanfaatkan oleh sebanyak mungkin desa di seluruh Nusantara. </p>
                <p>
                  Cara mengirimkan donasi dan informasi lebih lanjut ada di:
                </p>
                <a href="https://www.opendesa.id/donasi" class="btn btn-social btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Suplemen"><i class="fa fa-heart"></i> Donasi Pengembangan OpenSID</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection