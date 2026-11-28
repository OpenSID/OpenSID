{{-- Status IonCube Loader (T18, rilis Umum): inti OpenSID tidak membutuhkannya; add-on berbayar terenkripsi membutuhkannya. --}}
@php
    $ioncubeTersedia = \App\Services\Module\PemeriksaIonCube::loaderTersedia();
    $ioncubeVersi    = \App\Services\Module\PemeriksaIonCube::versiLoader();
    $ioncubeDilewati = \App\Services\Module\PemeriksaIonCube::modulDilewati();
@endphp
@if ($ioncubeTersedia)
    <div class="alert alert-success" role="alert">
        <p>IonCube Loader terpasang{{ $ioncubeVersi ? " (versi {$ioncubeVersi})" : '' }}. Add-on berbayar yang terenkripsi dapat dijalankan.</p>
    </div>
@else
    <div class="alert alert-info" role="alert">
        <p>IonCube Loader tidak terpasang. OpenSID tidak memerlukannya, tetapi add-on berbayar (Pelanggan, Anjungan, Buku Tamu) terenkripsi
            IonCube dan membutuhkan IonCube Loader untuk PHP {{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }}.
            Pasang dari https://www.ioncube.com/loaders.php bila Anda akan memakai add-on tersebut.</p>
    </div>
    @if ($ioncubeDilewati !== [])
        <div class="alert alert-warning" role="alert">
            <p>Add-on berikut terpasang tetapi dilewati karena IonCube Loader belum terpasang:
                <strong>{{ implode(', ', $ioncubeDilewati) }}</strong>.</p>
        </div>
    @endif
@endif
