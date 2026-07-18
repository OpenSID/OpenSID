<div class="tab-pane active">
    <div class="callout callout-info">
        <p style="margin-bottom:0">
            <i class="fa fa-flask"></i> <strong>Marketplace lokal.</strong> Mengajukan paket = mengambilnya (get) dari
            gudang ZIP marketplace lalu memasangnya — tanpa order/pembayaran ke Layanan. Daftarkan paket baru
            (dari URL repo) di tab <strong>Sumber</strong>.
        </p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                {!! form_open($form_action, 'class="form-horizontal" id="form-ajukan"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="module_name">Nama Paket</label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm select2 required" id="module_name" name="module_name" style="width:100%;">
                                <option value="">-- Pilih Paket --</option>
                                @foreach ($paket_repo as $m)
                                    <option value="{{ $m['name'] }}">
                                        {{ $m['name'] }}{{ $m['version'] !== '' ? ' (v' . $m['version'] . ')' : '' }}{{ $m['installed'] ? ' — terpasang' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @if (empty($paket_repo))
                                <small class="text-danger">Marketplace lokal kosong. Daftarkan paket di tab <strong>Sumber</strong>.</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-download"></i> Ajukan &amp; Pasang</button>
                </div>
                {!! form_close() !!}
            </div>
        </div>
    </div>
</div>
