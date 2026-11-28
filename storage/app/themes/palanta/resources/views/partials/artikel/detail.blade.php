@extends('theme::layouts.'.$layout)
@php
$post = $single_artikel;
$alt_slug = PREMIUM ? 'artikel' : 'first';
@endphp
@include('theme::commons.asset_highcharts')
@section('content')
<div class="box-def hoverstyle">
  <div class="box-def-inner">
    <div class="artike-page">
      @if (trim($single_artikel['kategori']) !== '') 
      <div class="bigmeta">
        <div class="artikel-meta">
          <div class="meta-item l-flex"><i class="fa fa-edit"></i>
            <p>{{ $single_artikel['kategori'] }}</p>
          </div>
        </div>
      </div>
      @endif
      <h1>{{ $single_artikel["judul"] }}</h1>
      <div class="artikel-meta" style="margin-bottom:10px;">
        <div class="meta-item l-flex"><i class="fa fa-calendar"></i>
          <p>{{ $single_artikel['tgl_upload_local'] }}</p>
        </div>
        <div class="meta-item l-flex"><i class="fa fa-user"></i>
          <p>{{ $single_artikel['owner'] }}</p>
        </div>
        <div class="meta-item l-flex"><i class="fa fa-eye"></i>
          <p>{{ hit($single_artikel['hit']) }} dibaca</p>
        </div>
      </div>
      <div class="artikel-image-page">
        <div class="image-column">

          @if ($single_artikel['gambar'] != '' && is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar'])) 
          <a data-fancybox="gallery" href="{{ AmbilFotoArtikel($single_artikel['gambar'], 'sedang') }}"><img
              src="{{ AmbilFotoArtikel($single_artikel['gambar'], 'sedang') }}" /></a>
          @endif
          @if ($single_artikel['gambar1'] != '' && is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar1'])) 
          <a data-fancybox="gallery" href="{{ AmbilFotoArtikel($single_artikel['gambar1'], 'sedang') }}"><img
              src="{{ AmbilFotoArtikel($single_artikel['gambar1'], 'sedang') }}" /></a>
          @endif
          @if ($single_artikel['gambar2'] != '' && is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar2'])) 
          <a data-fancybox="gallery" href="{{ AmbilFotoArtikel($single_artikel['gambar2'], 'sedang') }}"><img
              src="{{ AmbilFotoArtikel($single_artikel['gambar2'], 'sedang') }}" /></a>
          @endif
          @if ($single_artikel['gambar3'] != '' && is_file(LOKASI_FOTO_ARTIKEL . "sedang_" . $single_artikel['gambar3'])) 
          <a data-fancybox="gallery" href="{{ AmbilFotoArtikel($single_artikel['gambar3'], 'sedang') }}"><img
              src="{{ AmbilFotoArtikel($single_artikel['gambar3'], 'sedang') }}" /></a>
          @endif
        </div>
      </div>
      <div class="artikel-inner">

        @if ($single_artikel['id_kategori'] == 1000) 
        <table width="100%" class="table table-striped" style="margin-bottom:15px;">
          <tr>
            <td>Tanggal & Jam</td>
            <td width="20px">:</td>
            <td>{{ tgl_indo2($detail_agenda['tgl_agenda']) }}</td>
          </tr>
          <tr>
            <td>Lokasi</td>
            <td width="20px">:</td>
            <td>{{ $detail_agenda['lokasi_kegiatan'] }}</td>
          </tr>
          <tr>
            <td>Koordinator</td>
            <td width="20px">:</td>
            <td>{{ $detail_agenda['koordinator_kegiatan'] }}</td>
          </tr>
        </table>
        @endif
        @if ($single_artikel['dokumen'] != '' && is_file(LOKASI_DOKUMEN . $single_artikel['dokumen'])) 
        <div class="c-flex" style="margin-bottom:15px;">
          <p>Unduh Lampiran: <a href='{{ site_url("first/unduh_dokumen_artikel/{$single_artikel['id']}") }}'
              title=""><b>{{ $single_artikel['link_dokumen'] }}</b></a></p>
        </div>
        @endif
        <div class="artikel-inner-content">
          {!! $single_artikel["isi"] !!}
        </div>
      </div>      
      @include('theme::commons.sticky_share', [
					'link' => $single_artikel['url_slug'],
					'judul' => htmlspecialchars($single_artikel["judul"])
				])

      @if (!empty($komentar)) 
      <div class="comment-page">
        <h3 style="margin-bottom:10px;">Semua Komentar</h3>
        <div class="box-body">
          @foreach ($komentar as $data) 
          <table class="table table-bordered table-striped dataTable table-hover">
            <thead class="bg-gray disabled color-palette">
              <tr>
                <th colspan="2" style="text-align: left;"><i class="fa fa-comment"></i> {{ $data['owner'] }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2">
                  <font color='green'><small>{{ tgl_indo2($data['tgl_upload']) }}</small></font>
                  <br />{{ $data['komentar'] }}
                </td>
              </tr>
              <tr>
                <td width="10%"></td>
                <td>
                  @if (count($data['children']) > 0) 
                  @foreach ($data['children'] as $children) 
                  <table class="table table-bordered table-striped dataTable table-hover">
                    <thead class="bg-gray disabled color-palette">
                      <tr>
                        <th style="text-align: left;"><i class="fa fa-comment"></i> {{ $children['pengguna']['nama'] }}
                          <code>({{ $children['pengguna']['level'] }})</code></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <font color='green'><small>{{ tgl_indo2($children['tgl_upload']) }}</small></font>
                          <br />{{ $children['komentar'] }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  @endforeach
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
          @endforeach
        </div>
      </div>
      @endif

      @if ($single_artikel['boleh_komentar'] == 1) 
      <div class="comment-page">
        <h3 style="margin-bottom:10px;">Kirim Komentar</h3>
        @php
						$notif = session('notif');
						$label = ($notif['status'] == -1) ? 'label-danger' : 'label-info';
				@endphp
        @if ($notif) 
        <div class="box-header {{ $label }}">{{ $notif['pesan'] }}</div>
        @endif

        <div class="card-body">
          <form class="form-horizontal" id="validasi" name="form"
            action="{{ ci_route("add_comment.{$single_artikel['id']}") }}" method="POST"
            onSubmit="return validasi(this);">
            <div class="row l-flex mb-10">
              <label for="inputName" class="col-md-3 custom-label">User Name</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="owner" maxlength="50" placeholder="ketik di sini"
                  value="{{ $notif['data']['owner'] }}">
              </div>
            </div>
            <div class="row l-flex mb-10">
              <label for="inputName" class="col-md-3 custom-label">No. Hp</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="no_hp" maxlength="15" placeholder="ketik di sini"
                  value="{{ $notif['data']['no_hp'] }}">
              </div>
            </div>
            <div class="row l-flex mb-10">
              <label for="inputName" class="col-md-3 custom-label">Email</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="email" maxlength="50" placeholder="email@gmail.com"
                  value="{{ $notif['data']['email'] }}">
              </div>
            </div>
            <div class="row mb-10">
              <label for="inputName" class="col-md-3 custom-label">Isi Komentar</label>
              <div class="col-md-9">
                <textarea class="form-control" name="komentar">{{ $notif['data']['komentar'] }}</textarea>
              </div>
            </div>
            <div class="row mb-10 captha">
              <div class="col-md-3 text-center">
                <img id="captcha" src="{{ site_url('captcha') }}" alt="CAPTCHA Image" class="max-w-full h-auto">
                <button type="button" class="btn btn-primary btn-sm" style="margin-top: 5px;"
                  onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">[Ganti
                  Gambar]</button>
              </div>
              <div class="col-md-9">
                <label for="inputName" class="custom-label">Isikan Jawaban Dibawah</label>
                <input type="text" name="captcha_code" class="form-control" maxlength="6" placeholder="Ketik disini" />
              </div>
            </div>
            <div class="r-flex">
              <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
            </div>
          </form>
        </div>
      </div>
      @endif

      @if ($single_artikel['boleh_komentar'] == 1) 
      <div class="comment-page">
        <h3 style="margin-bottom:10px;">Komentar Facebook</h3>
        <div class="fb-comments" data-href="{{ $single_artikel['url_slug'] }}" width="100%"
          data-numposts="5"></div>
      </div>
      @endif

    </div>
  </div>
</div>
@endsection