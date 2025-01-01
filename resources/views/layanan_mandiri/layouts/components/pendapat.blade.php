<div
    class="modal fade"
    id="pendapat"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    data-backdrop="false"
    data-keyboard="false"
>
    <div class="modal-dialog notifikasi">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>BERIKAN PENILAIAN ANDA TERHADAP PELAYANAN KAMI</h4>
                @foreach (unserialize(NILAI_PENDAPAT) as $key => $value)
                    <a href="{{ site_url("layanan-mandiri/pendapat/{$key}") }}" class="btn btn-app pendapat">
                        <img src="{{ base_url(PENDAPAT . underscore($value, true, true) . '.png') }}">
                        <p>{{ $value }}</p>
                    </a>
                @endforeach
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
                <a href="{{ site_url('layanan-mandiri/keluar') }} " class="btn btn-success">Lain Kali</a>
            </div>
        </div>
    </div>
</div>
