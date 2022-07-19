{!! form_open_multipart(route('setting.update'), 'class="form-group" id="main_setting"') !!}
  
  <div class="modal-body">

    @foreach ($list_setting as $set)

      @php $key = ucwords(str_replace('_', ' ', $set->key)) @endphp

      @if ($set->key != 'penggunaan_server' && $set->jenis != 'upload' && $set->kategori == $kategori)
        
        @php
          $set->kategori = ($set->kategori == 'setting_analisis' && config_item('demo_mode')) ? 'readonly' : $set->kategori;
          $set->value    = ($set->key == 'layanan_opendesa_token' && config_item('demo_mode')) ? '' : $set->value;
        @endphp

        <div class="form-group" id="form_{{ $set->key }}">
          <label>{{ $key }}</label>
          @if ($set->jenis == 'option')
            @if ($set->key == 'tampilan_anjungan_slider')
            <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
              @foreach ($daftar_album as $option)
              <option value="{{ $option['id'] }}" @selected($set->value == $option['id'])>{{ $option['nama'] }}</option>
              @endforeach
            </select>
            @else
            <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
              @foreach ($set->options as $option)
              <option value="{{ $option->id }}" @selected($set->value == $option->id)>{{ $option->value }}</option>
              @endforeach
            </select>
            @endif
          @elseif ($set->jenis == 'option-kode')
              <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
                @foreach ($set->options as $option)
                <option value="{{ $option->kode }}" @selected($set->value == $option->kode)>{{ $option->value }}</option>
                @endforeach
              </select>
          @elseif ($set->jenis == 'option-value')
              <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
                @foreach ($set->options as $option)
                <option value="{{ $option->value }}" @selected($set->value == $option->value)>{{ $option->value }}</option>
                @endforeach
              </select>
          @elseif ($set->key == 'timezone')
              <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
                <option value="Asia/Jakarta" @selected($set->value == 'Asia/Jakarta')>Asia/Jakarta</option>
                <option value="Asia/Makassar" @selected($set->value == 'Asia/Makassar')>Asia/Makassar</option>
                <option value="Asia/Jayapura" @selected($set->value == 'Asia/Jayapura')>Asia/Jayapura</option>
              </select>
          @elseif ($set->key == 'sumber_gambar_slider')
              <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
                <option value="1" @selected($set->value == 1)>Gambar utama artikel terbaru</option>
                <option value="2" @selected($set->value == 2)>Gambar utama artikel terbaru yang masuk ke slider atas</option>
                <option value="3" @selected($set->value == 3)>Gambar dalam album galeri yang dimasukkan ke slider</option>
              </select>
          @elseif ($set->jenis == 'boolean')
            <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
            <option value="1" @selected($set->value == 1)>Ya</option>
              <option value="0" @selected($set->value == 0)>Tidak</option>
            </select>
          @elseif ($set->key == 'web_theme')
              <select class="form-control input-sm required select2" id="{{ $set->key }}" name="{{ $set->key }}">
                @foreach ($list_tema as $tema)
                  <option value="{{ $tema }}" @selected($set->value == $tema)>{{ $tema }}</option>
                @endforeach
              </select>
          @elseif ($set->jenis == 'datetime')
              <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl_1" id="{{ $set->key }}" name="{{ $set->key }}" type="text" value="{{ $set->value }}">
              </div>
          @elseif ($set->jenis == 'textarea')
              <textarea @disabled($set->kategori == 'readonly') class="form-control input-sm" name="{{ $set->key }}" placeholder="{{ $set->keterangan }}" rows="5">{{ $set->value }}</textarea>
          @elseif ($set->jenis == 'unggah')
            <div class="input-group">
              <input type="text" class="form-control input-sm" id="file_path" name="{{ $set->key }}">
              <input type="file" class="hidden" id="file" name="{{ $set->key }}">
              <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-sm" id="file_browser"><i class="fa fa-search"></i>&nbsp;</button>
                <a href="{{ file_exists(FCPATH . $set->value) ? asset($set->value, false) : asset('images/kehadiran/bg.jpg') }}" class="btn btn-danger btn-sm" title="Lihat Gambar" target="_blank"><i class="fa fa-eye"></i>&nbsp;</a>
              </span>
            </div>
          @else
              @if ($set->key == 'mac_adress_kehadiran')
                <input id="{{ $set->key }}" name="{{ $set->key }}" class="form-control input-sm mac_address" type="text" value="{{ $set->value }}" placeholder="00:1B:44:11:3A:B7" />
              @elseif ($set->key == 'ip_adress_kehadiran')
                <input id="{{ $set->key }}" name="{{ $set->key }}" class="form-control input-sm ip_address" type="text" value="{{ $set->value }}" placeholder="127.0.0.1" />
              @elseif ($set->key == 'id_pengunjung_kehadiran')
                <input id="{{ $set->key }}" name="{{ $set->key }}" class="form-control input-sm alfanumerik" type="text" value="{{ $set->value }}" placeholder="ad02c373c2a8745d108aff863712fe92" />
              @else
                <input id="{{ $set->key }}" name="{{ $set->key }}" class="form-control input-sm {{ jecho($set->jenis == 'int', false, 'digits') }}" type="text" value="{{ $set->value }}" @disabled($set->kategori == 'readonly')></input>
              @endif
          @endif
          <label><code>{{ $set->keterangan }}</code></label>
        </div>
      @endif
    @endforeach

  </div>
  <div class="modal-footer">
    <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
    <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
  </div>
</form>

@push('scripts')
<script src="{{ asset('js/validasi.js') }}"></script>
<script>
  $(document).ready(function() {
    $("#main_setting").validate();
  })
</script>
@endpush