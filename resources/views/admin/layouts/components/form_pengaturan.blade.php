{!! form_open(route('setting.update'), 'class="form-horizontal" id="main_setting"') !!}
  
  <div class="modal-body">

    @foreach ($list_setting as $set)

      @php $key = ucwords(str_replace('_', ' ', $set->key)) @endphp

      @if ($set->key != 'penggunaan_server' && $set->jenis != 'upload' && in_array($set->kategori, $kategori) && ($set->key != 'token_opensid'))
        
        @php
          $set->kategori = ($set->kategori == 'setting_analisis' && config_item('demo_mode')) ? 'readonly' : $set->kategori;
          $set->value    = ($set->key == 'layanan_opendesa_token' && config_item('demo_mode')) ? '' : $set->value;
        @endphp

        <div class="form-group" id="form_{{ $set->key }}">
          <label class="col-sm-12 col-md-3" for="nama">{{ $key }}</label>
          @if ($set->jenis == 'option')
          <div class="col-sm-12 col-md-4">
            @if ($set->key == 'tampilan_anjungan_slider')
            <select class="form-control input-sm" id="{{ $set->key }}" name="{{ $set->key }}">
              @foreach ($daftar_album as $option)
              <option value="{{ $option['id'] }}" {{ selected($set->value, $option['id']) }}>{{ $option['nama'] }}</option>
              @endforeach
            </select>
            @else
            <select class="form-control input-sm" id="{{ $set->key }}" name="{{ $set->key }}">
              @foreach ($set->options as $option)
              <option value="{{ $option->id }}" {{ selected($set->value, $option->id) }}>{{ $option->value }}</option>
              @endforeach
            </select>
            @endif
          </div>
          @elseif ($set->jenis == 'option-kode')
            <div class="col-sm-12 col-md-4">
              <select class="form-control input-sm" id="{{ $set->key }}" name="{{ $set->key }}">
                @foreach ($set->options as $option)
                <option value="{{ $option->kode }}" {{ selected($set->value, $option->kode) }}>{{ $option->value }}</option>
                @endforeach
              </select>
            </div>
          @elseif ($set->jenis == 'option-value')
            <div class="col-sm-12 col-md-4">
              <select class="form-control input-sm" id="{{ $set->key }}" name="{{ $set->key }}">
                @foreach ($set->options as $option)
                <option value="{{ $option->value }}" {{ selected($set->value, $option->value) }}>{{ $option->value }}</option>
                @endforeach
              </select>
            </div>
          @elseif ($set->key == 'timezone')
            <div class="col-sm-12 col-md-4">
              <select class="form-control input-sm" name="{{ $set->key }}" >
                <option value="Asia/Jakarta" {{ selected($set->value, 'Asia/Jakarta') }}>Asia/Jakarta</option>
                <option value="Asia/Makassar" {{ selected($set->value, 'Asia/Makassar') }}>Asia/Makassar</option>
                <option value="Asia/Jayapura" {{ selected($set->value, 'Asia/Jayapura') }}>Asia/Jayapura</option>
              </select>
            </div>
          @elseif ($set->key == 'sumber_gambar_slider')
            <div class="col-sm-12 col-md-4">
              <select class="form-control input-sm" id="{{ $set->key }}" name="{{ $set->key }}">
                <option value="1" {{ selected($set->value, 1) }}>Gambar utama artikel terbaru</option>
                <option value="2" {{ selected($set->value, 2) }}>Gambar utama artikel terbaru yang masuk ke slider atas</option>
                <option value="3" {{ selected($set->value, 3) }}>Gambar dalam album galeri yang dimasukkan ke slider</option>
              </select>
            </div>
          @elseif ($set->jenis == 'boolean')
            <div class="col-sm-12 col-md-4">
              <select class="form-control input-sm" id="{{ $set->key }}" name="{{ $set->key }}">
              <option value="1" {{ selected($set->value, 1) }}>Ya</option>
                <option value="0" {{ selected($set->value, 0) }}>Tidak</option>
              </select>
            </div>
          @elseif ($set->key == 'web_theme')
            <div class="col-sm-12 col-md-4">
              <select class="form-control input-sm" name="{{ $set->key }}" >
                @foreach ($list_tema as $tema)
                  <option value="{{ $tema }}" {{ selected($set->value, $tema) }}>{{ $tema }}</option>
                @endforeach
              </select>
            </div>
          @elseif ($set->jenis == 'datetime')
            <div class="col-sm-12 col-md-4">
              <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl_1" id="{{ $set->key }}" name="{{ $set->key }}" type="text" value="{{ $set->value }}">
              </div>
            </div>
          @elseif ($set->jenis == 'textarea')
            <div class="col-sm-12 col-md-4">
              <textarea {{ jecho($set->kategori, 'readonly', 'disabled') }} class="form-control input-sm" name="{{ $set->key }}" placeholder="{{ $set->keterangan }}" rows="5">{{ $set->value }} </textarea>
            </div>
          @else
            <div class="col-sm-12 col-md-4">
              <input id="{{ $set->key }}" name="{{ $set->key }}" class="form-control input-sm {{ ($set->jenis != 'int') || print 'digits' }}" type="text" value="{{ $set->value }}" {{ jecho($set->kategori, 'readonly', 'disabled') }}></input>
            </div>
          @endif
          <label class="col-sm-12 col-md-5 pull-left" for="nama">{{ $set->keterangan }}</label>
        </div>
      @endif
    @endforeach

  </div>
  <div class="modal-footer">
    <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
    <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
  </div>
</form>

@push('scripts')
<script>
  $(document).ready(function() {
    $("#main_setting").validate();
  })
</script>
@endpush