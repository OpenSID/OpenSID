@if ((int) $surat->masa_berlaku > 0)
    @include('admin.layouts.components.datetime_picker')

    <div class="form-group">
        <label for="mulai_berlaku" class="col-sm-3 control-label">Berlaku Dari - Sampai</label>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input title="Pilih Tanggal" id="tgl_mulai" class="form-control input-sm required" name="mulai_berlaku"
                    type="text" />
            </div>
        </div>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input title="Pilih Tanggal" id="tgl_akhir" class="form-control input-sm required" name="berlaku_sampai"
                    type="text" data-masa-berlaku="{{ $surat->masa_berlaku }}"
                    data-satuan-masa-berlaku="{{ $surat->satuan_masa_berlaku }}" />
            </div>
        </div>
    </div>
@endif
