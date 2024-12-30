@push('css')
    <style>
        .modal-content {
            border-radius: 5px;
            background: #ffffff;
            border: 2px solid black;
        }

        .notifikasi {
            top: 30%;
        }

        .modal-dialog {
            background: rgb(0 0 0 / 0%);
            padding: 5px;
        }

        .modal-content {
            border-radius: 5px;
            background: #ffffff;
            border: 2px solid black;
        }

        a.pendapat {
            padding: 10px !important;
            margin: 10px !important;
            color: #000 !important;
            font-weight: bold;
        }

        .pendapat {
            width: 100px;
            height: auto !important;
            border-color: #fff0 !important;
            background-color: #fff0 !important;
        }

        a.pendapat>p {
            margin: 10px 0 0 0;
            font-size: 11px;
            text-transform: uppercase;
        }

        .btn-app .pendapat {
            padding: 15px 5px !important;
            margin: 0 0 10px 10px !important;
        }
    </style>
@endpush
<div class="modal fade" id="pendapat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog notifikasi">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>BERIKAN PENILAIAN ANDA TERHADAP PELAYANAN KAMI</h4>
                <?php foreach (unserialize(NILAI_PENDAPAT) as $key => $value) : ?>
                <a href="<?= site_url("layanan-mandiri/pendapat/{$key}") ?>" class="btn btn-app pendapat">
                    <img src="<?= base_url(PENDAPAT . underscore($value, true, true) . '.png') ?>">
                    <p>
                        <?= $value ?>
                    </p>
                </a>
                <?php endforeach; ?>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
                <a href="<?= site_url('layanan-mandiri/keluar') ?> " class="btn btn-success">Lain Kali</a>
            </div>
        </div>
    </div>
</div>
