@foreach ($surat['kode_isian'] as $item)
    @php
        $nama = isset($keyname) ? underscore($item->nama, true, true) . '_' . $keyname : underscore($item->nama, true, true);
        $class = buat_class($item->atribut, 'form-control input-sm', $item->required);
        $widthClass = 'col-sm-' . (isset($item->kolom) && $item->kolom >= 1 && $item->kolom < 8 ? $item->kolom : 8);
        $dataKaitkan = strlen($item->kaitkan_kode ?? '') > 10 ? "data-kaitkan='" . $item->kaitkan_kode . "'" : '';
    @endphp

    <div class="form-group">
        <label for="{{ $nama }}" class="col-sm-3 control-label">{{ $item->nama }}</label>
        <div class="{{ $widthClass }}">
            @if ($item->tipe == 'select-manual')
                <select name="{{ $nama }}" {!! $class !!} {!! $dataKaitkan !!}>
                    <option value="">-- {{ $item->deskripsi }} --</option>
                    @foreach ($item->pilihan as $pilih)
                        <option {{ selected(set_value($nama), $pilih) }} value="{{ $pilih }}">{{ $pilih }}</option>
                    @endforeach
                </select>
            @elseif ($item->tipe == 'select-otomatis')
                <select name="{{ $nama }}" {!! $class !!} placeholder="{{ $item->deskripsi }}">
                    <option value="">-- {{ $item->deskripsi }} --</option>
                    @foreach (ref($item->refrensi) as $pilih)
                        <option {{ selected(set_value($nama), $pilih->nama) }} value="{{ $pilih->nama }}">{{ $pilih->nama }}</option>
                    @endforeach
                </select>
            @elseif ($item->tipe == 'textarea')
                <textarea name="{{ $nama }}" {!! $class !!} placeholder="{{ $item->deskripsi }}">{{ set_value($nama) }}</textarea>
            @elseif (in_array($item->tipe, ['date', 'hari', 'hari-tanggal']))
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" {!! buat_class($item->atribut, 'form-control input-sm tgl', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
                </div>
            @elseif ($item->tipe == 'time')
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" {!! buat_class($item->atribut, 'form-control input-sm jam', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
                </div>
            @elseif ($item->tipe == 'datetime')
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" {!! buat_class($item->atribut, 'form-control input-sm tgl_jam', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
                </div>
            @else
                <input type="{{ $item->tipe }}" {!! $class !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
            @endif
        </div>
    </div>
@endforeach
