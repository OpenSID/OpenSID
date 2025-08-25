@if ($notif_percobaan)
    @php
        $sisaHari = ceil((strtotime($notif_percobaan['akhir']) - time()) / (60 * 60 * 24));
    @endphp
    <div class="row">
        <div class='col-md-12'>
            <div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;Informasi Penting!</h4>
                <p align="justify">
                    OpenSID <span class='btn-default text-red btn-xs'>Lisensi Percobaan</span> tersisa <code>{{ $sisaHari }}</code> Hari lagi, Silahkan hubungi pelaksana untuk upgrade ke lisensi premium.
                </p>
            </div>
        </div>
    </div>
@endif
