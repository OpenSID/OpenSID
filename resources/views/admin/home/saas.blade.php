@php
    $first = $saas->first();
    $sisaHari = $first ? (int) ceil($first->sisa_aktif) : null;
@endphp

@if ($saas->count() != 0 && $sisaHari < 21 && $sisaHari > 0)
    <div class="row">
        <div class='col-md-12'>
            <div class="callout callout-warning">
                <h4><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;Pengingat <b>{{ $first->nama }}</b>!</h4>
                <p align="justify">
                    Pelanggan yang terhomat,
                    <br>
                    @if ($sisaHari > 0)
                        Ini adalah pengingat <b>{{ $first->nama }}</b> akan segera berakhir dalam waktu {{ $sisaHari }} hari
                    @elseif ($sisaHari == 0)
                        Ini adalah pengingat <b>{{ $first->nama }}</b> akan berakhir <b>hari ini</b>
                    @else
                        <b>{{ $first->nama }}</b> sudah <b>berakhir</b> sejak {{ abs($sisaHari) }} hari yang lalu
                    @endif
                    <br>
                    Perlu diketahui bahwa jika layanan tidak diperpanjang, situs web atau layanan apa pun yang terkait <b>{{ $first->nama }}</b> akan berhenti bekerja. Perbarui sekarang untuk menghindari gangguan dalam layanan.
                </p>
            </div>
        </div>
    </div>
@endif