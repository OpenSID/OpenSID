@if ($notif_langganan)
    @if ($notif_langganan['status'] > 1)
        <div class="row">
            <div class='col-md-12'>
                <div class="callout callout-warning">
                    <h4><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;Pengingat Layanan Desa!</h4>
                    <p align="justify">
                        Pelanggan yang terhomat,
                        <br>
                        Ini adalah pengingat bahwa masa berlangganan layanan Anda akan segera berakhir.
                        <br>
                        Silakan lakukan perpanjangan layanan agar tetap bisa menggunakan versi terbaru dari
                        <?= config_item('nama_aplikasi') ?>.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endif
