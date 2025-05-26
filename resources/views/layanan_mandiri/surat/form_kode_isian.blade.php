@foreach ($surat['kode_isian'] as $item)
    @php
        $nama = isset($keyname) ? underscore($item->nama, true, true) . '_' . $keyname : underscore($item->nama, true, true);
        $class = buat_class($item->atribut, 'w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm', $item->required);
        $widthClass = match ($item->kolom) {
            12 => 'w-full',
            10 => 'w-5/6',
            9 => 'w-3/4',
            8 => 'w-2/3',
            6 => 'w-1/2',
            4 => 'w-1/3',
            3 => 'w-1/4',
            default => 'w-full',
        };
        $dataKaitkan = strlen($item->kaitkan_kode ?? '') > 10 ? "data-kaitkan='" . $item->kaitkan_kode . "'" : '';
    @endphp

    <div class="mb-4 {{ $widthClass }}">
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $item->nama }}</label>

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
            <div class="relative">
                <input type="text" {!! buat_class($item->atribut, 'w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm tgl pl-10', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
                <span class="absolute left-3 top-2.5 text-gray-500">
                    <i class="fa fa-calendar"></i>
                </span>
            </div>
        @elseif ($item->tipe == 'time')
            <div class="relative">
                <input type="text" {!! buat_class($item->atribut, 'w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm jam pl-10', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
                <span class="absolute left-3 top-2.5 text-gray-500">
                    <i class="fa fa-clock-o"></i>
                </span>
            </div>
        @elseif ($item->tipe == 'datetime')
            <div class="relative">
                <input type="text" {!! buat_class($item->atribut, 'w-full bg-white border border-gray-300 rounded px-3 py-2 text-sm tgl_jam pl-10', $item->required) !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
                <span class="absolute left-3 top-2.5 text-gray-500">
                    <i class="fa fa-calendar"></i>
                </span>
            </div>
        @else
            <input type="{{ $item->tipe }}" {!! $class !!} name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" value="{{ set_value($nama) }}" />
        @endif
    </div>
@endforeach
