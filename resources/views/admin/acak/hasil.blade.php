@extends('admin.layouts.index')

@section('title')
    <h1>Acak Data</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('database') }}">Pengaturan Database</a></li>
    <li><a href="{{ route('acak.index') }}">Acak Data</a></li>
    <li class="active">Hasil</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><strong>Hasil Acak Data</strong></h3>
        </div>
        <div class="box-body">
                @php
                    $detik   = (int) round($result->elapsedSeconds);
                    $durasi  = $detik >= 60 ? intdiv($detik, 60) . ' menit ' . ($detik % 60) . ' detik' : $detik . ' detik';
                    $periksa = array_sum($result->periksaResolved);
                @endphp

                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> Data berhasil diacak (de-identifikasi).
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <th style="width: 40%">Waktu proses</th>
                                <td>{{ $durasi }}</td>
                            </tr>
                            <tr>
                                <th>Baris diproses</th>
                                <td>{{ number_format($result->rowsProcessed) }}</td>
                            </tr>
                            <tr>
                                <th>Tabel dimodifikasi</th>
                                <td>{{ count($result->tables()) }}</td>
                            </tr>
                            <tr>
                                <th>NIK diganti</th>
                                <td>{{ number_format($result->niksReplaced) }}</td>
                            </tr>
                            <tr>
                                <th>No. KK diganti</th>
                                <td>{{ number_format($result->kksReplaced) }}</td>
                            </tr>
                            <tr>
                                <th>Nama diganti</th>
                                <td>{{ number_format($result->namesReplaced) }}</td>
                            </tr>
                            <tr>
                                <th>No. telepon diganti</th>
                                <td>{{ number_format($result->phonesReplaced) }}</td>
                            </tr>
                            @if ($result->photosGenerated > 0)
                                <tr>
                                    <th>Avatar dibuat</th>
                                    <td>{{ number_format($result->photosGenerated) }}</td>
                                </tr>
                            @endif
                            @if ($result->photosBlurred > 0)
                                <tr>
                                    <th>Foto dikabur</th>
                                    <td>{{ number_format($result->photosBlurred) }}</td>
                                </tr>
                            @endif
                            @if ($result->fotosArtikelProcessed > 0)
                                <tr>
                                    <th>Foto artikel diproses</th>
                                    <td>{{ number_format($result->fotosArtikelProcessed) }}</td>
                                </tr>
                            @endif
                            @if ($result->fotosGaleriProcessed > 0)
                                <tr>
                                    <th>Foto galeri diproses</th>
                                    <td>{{ number_format($result->fotosGaleriProcessed) }}</td>
                                </tr>
                            @endif
                            @if ($result->fotosMediaProcessed > 0)
                                <tr>
                                    <th>Foto media diproses</th>
                                    <td>{{ number_format($result->fotosMediaProcessed) }}</td>
                                </tr>
                            @endif
                            @if ($result->arsipReplaced > 0)
                                <tr>
                                    <th>Arsip surat diganti</th>
                                    <td>{{ number_format($result->arsipReplaced) }}</td>
                                </tr>
                            @endif
                            @if ($result->suratSintetisGenerated > 0)
                                <tr>
                                    <th>Surat sintetis dibuat</th>
                                    <td>{{ number_format($result->suratSintetisGenerated) }}</td>
                                </tr>
                            @endif
                            @if ($result->orphanedDeleted > 0)
                                <tr>
                                    <th>File usang dihapus</th>
                                    <td>{{ number_format($result->orphanedDeleted) }}</td>
                                </tr>
                            @endif
                            @if ($periksa > 0)
                                <tr>
                                    <th>Masalah periksa diselesaikan</th>
                                    <td>{{ number_format($periksa) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if (! empty($result->pendudukSample))
                    <h4>Contoh hasil (5 baris pertama)</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>NIK lama</th>
                                    <th>NIK baru</th>
                                    <th>Nama lama</th>
                                    <th>Nama baru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result->pendudukSample as $row)
                                    <tr>
                                        <td><code>{{ $row['nik_lama'] }}</code></td>
                                        <td><code>{{ $row['nik_baru'] }}</code></td>
                                        <td>{{ $row['nama_lama'] }}</td>
                                        <td>{{ $row['nama_baru'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (! empty($result->periksaResolved))
                    <div class="box box-solid">
                        <div class="box-header with-border"><b>Rincian penyelesaian /periksa</b></div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th>Jenis masalah</th><th class="text-right">Jumlah diperbaiki</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($result->periksaResolved as $jenis => $jumlah)
                                        <tr>
                                            <td><code>{{ $jenis }}</code></td>
                                            <td class="text-right">{{ number_format($jumlah) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (! empty($result->periksaResidual))
                    <div class="alert alert-warning">
                        <b>/periksa masih melaporkan:</b>
                        <ul class="mb-0">
                            @foreach ($result->periksaResidual as $masalah)
                                <li><code>{{ $masalah }}</code></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! empty($result->warnings))
                    <div class="alert alert-warning">
                        <b>Peringatan:</b>
                        <ul class="mb-0">
                            @foreach ($result->warnings as $warning)
                                <li>{{ $warning }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! empty($result->processedUrls))
                    <div class="alert alert-info" id="acak-cache-refresh">
                        <i class="fa fa-spinner fa-spin"></i>
                        Memperbarui cache browser untuk <strong>{{ count($result->processedUrls) }}</strong> foto yang diproses…
                    </div>
                    <script>
                        (function () {
                            var urls = @json($result->processedUrls);
                            if (!urls.length) { return; }

                            var done = 0;
                            var finish = function () {
                                if (++done < urls.length) { return; }
                                var el = document.getElementById('acak-cache-refresh');
                                if (el) {
                                    el.classList.remove('alert-info');
                                    el.classList.add('alert-success');
                                    el.innerHTML = '<i class="fa fa-check"></i> Cache foto browser telah diperbarui.';
                                }
                            };

                            // Muat ulang URL kanonik (tanpa ?v=) dengan cache:'reload' agar
                            // entri cache HTTP browser untuk URL yang benar-benar dirujuk halaman
                            // (galeri, artikel, dll.) ditimpa berkas baru — tanpa perlu hard refresh.
                            // Menambah ?v= hanya menghangatkan kunci cache berbeda dan tak berefek.
                            //
                            // Ambil dengan kolam terbatas (maks. CONCURRENCY sekaligus): menembakkan
                            // ribuan fetch serentak sekaligus membuat browser menolak sebagian
                            // (ERR_INSUFFICIENT_RESOURCES) sehingga foto itu tetap basi. Namun batas
                            // yang terlalu kecil membuat proses (ribuan URL) berjalan lama sehingga
                            // pengguna keburu pindah halaman sebelum selesai. Server disajikan via
                            // HTTP/2 (multipleks banyak stream di satu koneksi), jadi batas ~50 aman
                            // dan cepat. Kegagalan sesaat dicoba ulang sekali sebelum menyerah.
                            var CONCURRENCY = 50;
                            var next = 0;
                            var active = 0;

                            function bust(u, attempt, onDone) {
                                if (window.fetch) {
                                    fetch(u, { cache: 'reload', credentials: 'same-origin' })
                                        .then(onDone, function () {
                                            // Reject = galat jaringan (mis. ERR_INSUFFICIENT_RESOURCES);
                                            // coba ulang sekali sebelum menyerah.
                                            if (attempt < 1) { bust(u, attempt + 1, onDone); } else { onDone(); }
                                        });
                                } else {
                                    var img = new Image();
                                    img.onload = onDone;
                                    img.onerror = function () {
                                        if (attempt < 1) { bust(u, attempt + 1, onDone); } else { onDone(); }
                                    };
                                    img.src = u + (u.indexOf('?') === -1 ? '?' : '&') + 'v=' + Date.now();
                                }
                            }

                            function pump() {
                                while (active < CONCURRENCY && next < urls.length) {
                                    active++;
                                    bust(urls[next++], 0, function () {
                                        active--;
                                        finish();
                                        pump();
                                    });
                                }
                            }

                            pump();
                        })();
                    </script>
                @endif

                <a href="{{ route('acak.index') }}" class="btn btn-social btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
        </div>
    </div>
@endsection
