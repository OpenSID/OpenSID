<div class="box box-{{ $status == 1 ? 'success' : ($sistem == 1 ? 'info' : 'danger') }}">
    <div class="box-header with-border text-center">
        <strong>{{ $nama }}</strong>
        <div class="ribbon-wrapper">
            @php
                // Label kategori pakai `kategori` (distribusi), BUKAN `sistem`
                // (lokasi folder). Padanan Premium. Tema hasil merge bursa
                // (belum terpasang) tak punya kolom `kategori` -- default ke
                // Tema Pro, sama seperti perilaku lama ($sistem selalu 0).
                // KATEGORI_PREMIUM_EKSKLUSIF (mis. Wira) sama tab filter
                // "Tema Pro" dgn KATEGORI_PREMIUM -- beda hanya teks ribbon:
                // "Premium" (bonus eksklusif langganan) vs "Tema Pro" (bisa
                // dibeli satuan).
                // KATEGORI_MITRA (mis. Tema Tabanan): tema kerja sama
                // kabupaten/kota -- gratis untuk semua (ribbon biru seperti
                // "Umum"), teks ribbon "Tema Mitra". Padanan Premium.
                $kategoriTema = $kategori ?? \App\Models\Theme::KATEGORI_PREMIUM;
                $isKategoriUmum = $kategoriTema === \App\Models\Theme::KATEGORI_UMUM;
                $isKategoriMitra = $kategoriTema === \App\Models\Theme::KATEGORI_MITRA;
                $isKategoriEksklusif = $kategoriTema === \App\Models\Theme::KATEGORI_PREMIUM_EKSKLUSIF;
                $isKategoriGratis = $isKategoriUmum || $isKategoriMitra;
                $ribbonClass = $status == 1 ? 'btn-success' : ($isKategoriGratis ? 'btn-info' : 'btn-danger');
                $ribbonText = $status == 1 ? 'Aktif' : ($isKategoriMitra ? 'Tema Mitra' : ($isKategoriUmum ? 'Umum' : ($isKategoriEksklusif ? 'Premium' : 'Tema Pro')));
            @endphp
            <div class="{{ $ribbonClass }} ribbon">
                {{ $ribbonText }}
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="text-center">
            <center>
                @php $file = $asset_path . '/thumbnail/preview-1.jpg' @endphp
                @if (file_exists(FCPATH . $file))
                    <img style="width:100%; max-height: 160px;" src="{{ base_url($asset_path . '/thumbnail/preview-1.jpg') }}" class="img-responsive" alt="{{ $nama }}">
                @elseif ($thumbnail)
                    <img style="width:100%; max-height: 160px;" src="{{ $thumbnail }}" class="img-responsive" alt="{{ $nama }}">
                @else
                    <img style="max-height: 160px;" src="{{ asset('images/404-image-not-found.jpg') }}" class="img-responsive" alt="{{ $nama }}">
                @endif
            </center>
        </div>
        <br>
        <div class="text-center">
            @if ($status == 1)
                <a href="#" class="btn btn-social btn-success btn-sm" readonly><i class="fa fa-star"></i>Aktif</a>
            @elseif ($marketplace)
                @if ($providers)
                    <a href="{{ $providers }}" class="btn btn-social btn-info btn-sm" target="_blank"><i class="fa fa-eye"></i>Preview</a>
                @endif
                <a href="https://opendesa.id/tema-premium" class="btn btn-social btn-warning btn-sm" target="_blank"><i class="fa fa-info"></i>Hubungi</a>
                @if ($themeOrder?->firstWhere('nama', $nama))
                    <form action="{{ site_url('theme/unduh') }}" method="POST" style="display:inline;">
                        <input type="hidden" name="nama" value="{{ $nama }}">
                        <input type="hidden" name="url" value="{{ $url }}">
                        <button type="submit" class="btn btn-social bg-navy btn-sm" title="Unduh Tema">
                            <i class="fa fa-download"></i> Unduh
                        </button>
                    </form>
                @endif
            @else
                @php
                    // rencana-refaktor-tema-siappakai.md §1.4/Fase 2/Fase 7:
                    // padanan Premium -- Umum juga bisa dihost SiapPakai
                    // (Opensid::UMUM), yang menjalankan Fase 1 sync yang sama.
                    $berhakAktivasi = \App\Actions\Theme\ActivateTheme::berhakAktivasi($kategoriTema, $slug, $nama);
                @endphp
                @if ($berhakAktivasi && can('u'))
                    <a href="{{ site_url('theme/aktifkan/' . $id) }}" class="btn btn-info btn-sm" title="Aktifkan Tema"><i class="fa fa-star-o"></i></a>
                @elseif (! $berhakAktivasi)
                    {{-- Belum berhak (khusus SiapPakai) -- tautan pemesanan,
                         bukan penolakan tanpa jalan keluar. TODO
                         (Layanan_OpenDESA#1371): sama seperti Premium --
                         endpoint pemesanan mandiri self-service ADA
                         (POST /api/v1/pemesanan) tapi belum mendukung tema,
                         dan belum ada client produksi yang memanggilnya. --}}
                    <a href="{{ config_item('website') . '/tema-pro-opensid' }}" class="btn btn-social btn-warning btn-sm" target="_blank" title="Pesan Tema Ini"><i class="fa fa-info"></i>Hubungi</a>
                @endif
                @if (!cache('siappakai') && !setting('multi_desa') && can('h') && $sistem !== 1)
                    <a href="#" data-href="{{ site_url('theme/delete/' . $id) }}" class="btn btn-danger btn-sm" title="Hapus Tema" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                @endif
            @endif
            @if (!$marketplace && can('u'))
                <a href="{{ site_url('theme/pengaturan/' . $id) }}" class="btn bg-navy btn-sm" title="Pengaturan Tema"><i class="fa fa-cog"></i></a>
            @endif
        </div>
    </div>

</div>
