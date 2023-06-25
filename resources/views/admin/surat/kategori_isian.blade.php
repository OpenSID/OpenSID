@foreach ($form_kategori as $key => $kategori)
{{-- @dd($kategori) --}}
<div class="form-group subtitle_head" id="a_saksi2">
    <label class="col-sm-3 control-label" for="status">{{ strtoupper($key) }}</label>
    <input name="anchor" type="hidden" value="<?= $anchor; ?>" />
    <div class="btn-group col-sm-8" data-toggle="buttons">
        {{-- <label class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label active">
            <input id="saksi2_1" type="radio" name="saksi2" class="form-check-input" type="radio" value="1" checked
                autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Desa
        </label> --}}
        {{-- <label id="label_saksi2_2"
            class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label">
            <input id="saksi2_2" disabled type="radio" name="saksi2" class="form-check-input" type="radio" value="2"
                autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Luar Desa
        </label> --}}
    </div>
</div>
<div class="form-group saksi2_desa">
    <label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon"
        style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA {{ strtoupper($key)
            }}</strong></label>
</div>
<div class="form-group saksi2_desa">
    <label for="saksi2_desa" class="col-sm-3 control-label"><strong>NIK / Nama</strong></label>
    <div class="col-sm-5">
        <select class="form-control select2 input-sm select2-nik-ajax" name="id_pend_{{$key}}"
            style="width:100%;" data-url="<?= site_url('surat/list_penduduk_ajax')?>">
        </select>
    </div>
</div>

{{-- kode isia kategori --}}
<?php foreach ($kategori['kode_isian'] as $item): ?>
<?php $nama = underscore($item->nama, true, true); ?>
<div class="form-group">
    <label for="<?= $item->nama ?>" class="col-sm-3 control-label">
        <?= $item->nama ?>
    </label>
    <?php if ($item->tipe == 'select-manual'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm ', $item->atribut) : 'class="form-control input-sm"' ?>
            placeholder="<?= $item->deskripsi ?>">
            <option value="">--<?= $item->deskripsi ?>--</option>
            <?php foreach ($item->pilihan as $key => $pilih): ?>
            <option value="<?= $pilih ?>">
                <?= $pilih ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <?php elseif ($item->tipe == 'select-otomatis'): ?>
    <div class="col-sm-4">
        <select name="<?= $nama ?>" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm ',
            $item->atribut) : 'class="form-control input-sm"' ?>
            placeholder="
            <?= $item->deskripsi ?>">
            <option value="">--<?= $item->deskripsi ?> --</option>
            <?php foreach (ref($item->refrensi) as $key => $pilih): ?>
            <option value="<?= $pilih->nama ?>">
                <?= $pilih->nama ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <?php elseif ($item->tipe == 'textarea'): ?>
    <div class="col-sm-8">
        <textarea name="<?= $nama ?>" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm ', $item->atribut) : 'class="form-control input-sm"' ?> placeholder="<?= $item->deskripsi ?>"></textarea>
    </div>
    <?php elseif ($item->tipe == 'date' || $item->tipe == 'hari' || $item->tipe == 'hari-tanggal'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm tgl ',
            $item->atribut) : 'class="form-control input-sm tgl"' ?>
            name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php elseif ($item->tipe == 'time'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm jam ',
            $item->atribut) : 'class="form-control input-sm jam"' ?>
            name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php elseif ($item->tipe == 'datetime'): ?>
    <div class="col-sm-3 col-lg-2">
        <div class="input-group input-group-sm date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm tgl_jam ',
            $item->atribut) : 'class="form-control input-sm tgl_jam"' ?>
            name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
        </div>
    </div>
    <?php else: ?>
    <div class="col-sm-8">
        <input type="<?= $item->tipe ?>" <?=$item->atribut ? str_replace('class="', 'class="form-control input-sm ',
        $item->atribut) : 'class="form-control input-sm"' ?> name="<?= $nama ?>" placeholder="<?= $item->deskripsi ?>" />
    </div>
    <?php endif ?>
</div>
<?php endforeach ?>


@endforeach


@push('scripts')
<script>

    $('.select2-nik-ajax').select2({
	  ajax: {
	    url: function () {
	      return $(this).data('url');
	    },
	    dataType: 'json',
	    delay: 250,
	    data: function (params) {
	      return {
	        q: params.term || '', // search term
	        page: params.page || 1,
 	        filter_sex: $(this).data('filter-sex')
	      };
	    },
	    processResults: function (data, params) {
	      // parse the results into the format expected by Select2
	      // since we are using custom formatting functions we do not need to
	      // alter the remote JSON data, except to indicate that infinite
	      // scrolling can be used
	      // params.page = params.page || 1;

	      return {
	        results: data.results,
	        pagination: data.pagination
	      };
	    },
	    cache: true
	  },
		templateResult: function (penduduk) {
			if (!penduduk.id) {
			  return penduduk.text;
			}
			var _tmpPenduduk = penduduk.text.split('\n');
			var $penduduk = $(
			  '<div>'+_tmpPenduduk[0]+'</div><div>'+_tmpPenduduk[1]+'</div>'
			);
			return $penduduk;
		},
	  placeholder: '--  Cari NIK / Tag ID Card / Nama Penduduk --',
	  minimumInputLength: 0,
	});
</script>
@endpush