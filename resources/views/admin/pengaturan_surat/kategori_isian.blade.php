<h5><b>Kode Isian</b></h5>
<div class="table-responsive">
    <table class="table table-hover table-striped kode-isian">
        <tbody id="dragable-{{ $item }}">
            <tr style="font-weight: bold;">
                <td>#</td>
                <td>TIPE</td>
                <td>NAMA</td>
                <td>LABEL</td>
                <td>PLACEHOLDER</td>
                <td class="padat">HARUS DIISI</td>
                <td>KOLOM</td>
                <td>ATRIBUT</td>
                <td class="isian-pilihan">PILIHAN</td>
                <td>AKSI</td>
            </tr>
            @php $jumlah_isian = 0; @endphp
            @foreach ($kategori as $key => $value)
                @php $jumlah_isian++; @endphp
                <tr class="duplikasi kategori ui-sortable-handle" id="gandakan-{{ $value->kategori }}-{{ $key }}" data-id="{{ $key }}">
                    <td><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></td>
                    <td>
                        <select class="form-control input-sm pilih_tipe" name="kategori_tipe_kode[{{ $value->kategori }}][]">
                            <option value="" selected>Pilihan Tipe</option>
                            @foreach ($attributes as $attr_key => $attr_value)
                                <option value="{{ $attr_key }}" @selected($attr_key == $value->tipe)>{{ $attr_value }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="kategori_nama_kode[{{ $value->kategori }}][]" class="form-control input-sm isian" value="{{ $value->nama }}" placeholder="Masukkan Nama" @disabled($value->tipe == '')>
                    </td>
                    <td><input type="text" name="kategori_label_kode[{{ $value->kategori }}][]" class="form-control input-sm isian" value="{{ $value->label ?? '' }}" placeholder="Masukkan Label" @disabled($value->tipe == '')>
                    </td>
                    <td><input type="text" name="kategori_deskripsi_kode[{{ $value->kategori }}][]" class="form-control input-sm isian" value="{{ $value->deskripsi }}" placeholder="Masukkan Placeholder" @disabled($value->tipe == '')></td>
                    <td class="text-center">
                        <input class="isian-required" type="checkbox" value="1" @checked($value->required) @disabled($value->tipe == '') name="kategori_required_kode[{{ $value->kategori }}][{{ $jumlah_isian - 1 }}]">
                    </td>
                    <td class="text-center">
                        <select class="form-control input-sm" name="kategori_kolom[{{ $value->kategori }}][]">
                            <option value="" selected>Pilihan lebar kolom</option>
                            @foreach (range(1, 12) as $itemKolom)
                                <option value="{{ $itemKolom }}" @selected($itemKolom == $value->kolom)>col-{{ $itemKolom }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-atribut" name="kategori_atribut_kode[{{ $value->kategori }}][]" rows="5" placeholder="Masukkan Atribut" @disabled($value->tipe == '')>{{ $value->atribut }}</textarea>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-pilihan {{ $value->tipe == 'select-otomatis' || $value->tipe == 'select-manual' ? 'hide' : '' }}" name="kategori_pilihan_kode[{{ $value->kategori }}][]" rows="5" placeholder="Masukkan Pilihan"
                            {{ $value->tipe != 'select-otomatis' || $value->tipe != 'select-manual' ? 'disabled' : '' }}
                        >{{ json_encode($value->pilihan) }}
                        </textarea>
                        <select class="{{ $value->tipe == 'select-manual' ? 'select2' : 'hide' }} form-control selectinput-sm isian select-manual" name="kategori_pilihan_kode[{{ $value->kategori }}][{{ $jumlah_isian }}][]" multiple placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                            @foreach ($value->pilihan as $pilihan)
                                <option value="{{ $pilihan }}" selected>{{ $pilihan }}</option>
                            @endforeach
                        </select>
                        <select class="form-control input-sm isian isian-referensi @display($value->tipe == 'select-otomatis')" name="kategori_referensi_kode[{{ $value->kategori }}][]" placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                            <option value="" selected>Pilihan Referensi</option>
                            @foreach (\App\Enums\ReferensiEnum::all() as $label => $val)
                                <option value="{{ $val }}" @selected($val == $value->refrensi)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="padat">
                        <div class="btn-group-vertical">
                            <button type="button" class="btn btn-flat btn-danger btn-sm hapus-kode" title="Hapus Kode Isian"><i class='fa fa-trash-o'></i></button>
                            <button type="button" class="btn btn-flat btn-warning btn-sm pindah-kode" title="Pindah Kode Isian"><i class='fa fa-exchange'></i></button>
                            <button type="button" class="btn btn-flat btn-primary btn-sm kaitkan-kode" title="Kaitkan Kode Isian"><i class='fa fa-link'></i></button>
                            <input type="hidden" class="form-control input-sm kaitkan hide" name="kategori_kaitkan_kode[{{ $value->kategori }}][]" value="{{ $value->kaitkan_kode ?? '' }}" />
                        </div>
                    </td>
                </tr>
            @endforeach
            @if ($jumlah_isian == 0)
                <tr class="duplikasi kategori ui-sortable-handle" id="gandakan-{{ $item }}-0" data-id="0">
                    <td><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></td>
                    <td>
                        <select class="form-control input-sm pilih_tipe" name="kategori_tipe_kode[{{ $item }}][]">
                            <option value="" selected>Pilihan Tipe</option>
                            @foreach ($attributes as $attr_key => $attr_value)
                                <option value="{{ $attr_key }}" @selected($attr_key == 1)>
                                    {{ $attr_value }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="kategori_nama_kode[{{ $item }}][]" class="form-control input-sm isian" placeholder="Masukkan Nama" @disabled($value->tipe == '')></td>
                    <td><input type="text" name="kategori_label_kode[{{ $item }}][]" class="form-control input-sm isian" placeholder="Masukkan Label" @disabled($value->tipe == '')></td>
                    <td><input type="text" name="kategori_deskripsi_kode[{{ $item }}][]" class="form-control input-sm isian" placeholder="Masukkan Placeholder" @disabled($value->tipe == '')></td>
                    <td class="text-center">
                        <input class="isian-required" type="checkbox" value="1" @checked($value->required) @disabled($value->tipe == '') name="kategori_required_kode[{{ $item }}][]">
                    </td>
                    <td class="text-center">
                        <select class="form-control input-sm" name="kategori_kolom[{{ $value->kategori }}][]">
                            <option value="" selected>Pilihan lebar kolom</option>
                            @foreach (range(1, 12) as $itemKolom)
                                <option value="{{ $itemKolom }}">col-{{ $itemKolom }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-atribut" name="kategori_atribut_kode[{{ $value->kategori }}][]" rows="5" placeholder="Masukkan Atribut" @disabled($value->tipe == '')>{{ $value->atribut }}</textarea>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-pilihan  @display($value->tipe != 'select-manual')" name="kategori_atribut_kode[{{ $item }}][]" rows="5" placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>{{ (string) $value->atribut }}
                        </textarea>
                        <select class="form-control input-sm isian select-manual @display($value->tipe == 'select-manual')" name="kategori_pilihan_kode[{{ $item }}][{{ $jumlah_isian }}][]" multiple placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                        </select>
                        <select class="form-control input-sm isian isian-referensi @display($value->tipe == 'select-otomatis')" name="kategori_referensi_kode[{{ $item }}][]" placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                            <option value="" selected>Pilihan Referensi</option>
                            @foreach (\App\Enums\ReferensiEnum::all() as $key => $value)
                                <option value="{{ $value }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="padat">
                        <div class="btn-group-vertical">
                            <button type="button" class="btn btn-flat btn-danger btn-sm hapus-kode" title="Hapus Kode Isian"><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-flat btn-warning btn-sm pindah-kode hide" title="Pindah Kode Isian"><i class='fa fa-exchange'></i></button>
                            <button type="button" class="btn btn-flat btn-primary btn-sm kaitkan-kode" title="Kaitkan Kode Isian"><i class='fa fa-link'></i></button>
                            <input type="hidden" class="form-control input-sm kaitkan hide" name="kategori_kaitkan_kode[{{ $value->kategori }}][]" value="" />
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <button type="button" class="btn btn-success btn-sm btn-block tambah-kode" data-type="gandakan-{{ $item }}" data-kategori="{{ $item }}"><i class="fa fa-plus"></i></button>
</div>

@push('scripts')
    <script>
        $("#dragable-{{ $item }}").sortable({
            cursor: 'row-resize',
            placeholder: 'ui-state-highlight',
            items: '.ui-sortable-handle'
        }).disableSelection();
    </script>
@endpush
