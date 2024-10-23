<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <input name="parrent" type="hidden" value="{{ $menu_utama['id'] ?? 0 }}" />
        @if ($menu_utama)
            <div class="form-group">
                <label class="control-label" for="menu_utama">Menu Utama</label>
                <select name="parrent" class="form-control input-sm required">
                    <option value="0">Menu Utama</option>
                    @forelse ($menu_utama as $key => $item)
                        <option value="{{ $key }}" @selected($key == $menu['parrent'])>{{ $item }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        @endif
        <div class="form-group">
            <label class="control-label" for="nama">Nama</label>
            <input name="nama" class="form-control input-sm required nomor_sk" maxlength="50" type="text" value="{{ $menu['nama'] }}" />
        </div>
        @if (!empty($menu['link']))
            <div class="form-group">
                <label class="control-label" for="link_sebelumnya">Link Sebelumnya</label>
                <input class="form-control input-sm" type="text" value="{{ $menu['link_url'] }}" disabled />
            </div>
        @endif
        <div class="form-group">
            <label class="control-label" for="link">Jenis Link</label>
            <select class="form-control input-sm required" id="link_tipe" name="link_tipe" onchange="ganti_jenis_link(this);">
                <option option value="">-- Pilih Jenis Link --</option>
                @foreach ($link_tipe as $id => $nama)
                    <option value="{{ $id }}" @selected($menu['link_tipe'] == $id)>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" id="jenis_link" style="@if (!$menu['link_tipe']) display:none; @endif">
            <label class="control-label" for="link">Link</label>
            <select id="artikel_statis" class="form-control input-sm jenis_link select2" name="{{ jecho($menu['link_tipe'], 1, 'link') }}" style="@if ($menu['link_tipe'] != 1) display:none; @endif">
                <option value="">-- Pilih Artikel Statis --</option>
                @foreach ($artikel_statis as $data)
                    <option value="artikel/{{ $data['id'] }}" @selected($menu['link'] == "artikel/{$data['id']}")>{{ $data['judul'] }}</option>
                @endforeach
            </select>
            <select id="kategori_artikel" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 8, 'link') }}" style="@if ($menu['link_tipe'] != 8) display:none; @endif">
                <option value="">-- Pilih Kategori Artikel --</option>
                @foreach ($kategori_artikel as $data)
                    <option value="kategori/{{ $data['slug'] }}" @selected($menu['link'] == "kategori/{$data['slug']}")>{{ $data['kategori'] }}</option>
                @endforeach
            </select>
            <select id="statistik_penduduk" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 2, 'link') }}" style="@if ($menu['link_tipe'] != 2) display:none; @endif">
                <option value="">-- Pilih Statistik Penduduk --</option>
                @foreach ($statistik_penduduk as $id => $nama)
                    <option value="statistik/{{ $id }}" @selected($menu['link'] == "statistik/{$id}")>{{ $nama }}</option>
                @endforeach
            </select>
            <select id="statistik_keluarga" class="form-control jenis_link input-sm" name="{{ jecho($menu['link_tipe'], 3, 'link') }}" style="@if ($menu['link_tipe'] != 3) display:none; @endif">
                <option value="">-- Pilih Statistik Keluarga --</option>
                @foreach ($statistik_keluarga as $id => $nama)
                    <option value="statistik/{{ $id }}" @selected($menu['link'] == "statistik/{$id}")>{{ $nama }}</option>
                @endforeach
            </select>
            <select id="statistik_program_bantuan" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 4, 'link') }}" style="@if ($menu['link_tipe'] != 4) display:none; @endif">
                <option value="">-- Pilih Statistik Program Bantuan --</option>
                @foreach ($statistik_kategori_bantuan as $id => $nama)
                    <option value="statistik/{{ $id }}" @selected($menu['link'] == "statistik/{$id}")>{{ $nama }}</option>
                @endforeach
                @foreach ($statistik_program_bantuan as $nama)
                    <option value="statistik/50{{ $nama['id'] }}" @selected($menu['link'] == "statistik/50{$nama['id']}")>{{ $nama['nama'] }}</option>
                @endforeach
            </select>
            <select id="statistik_kesehatan" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 12, 'link') }}" style="@if ($menu['link_tipe'] != 12) display:none; @endif">
                <option value="">-- Pilih Statistik Kesehatan --</option>
                <option value="data-kesehatan/stunting" @selected($menu['link'] == 'data-kesehatan/stunting')>Stunting</option>
            </select>
            <select id="statis_lainnya" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 5, 'link') }}" style="@if ($menu['link_tipe'] != 5) display:none; @endif">
                <option value="">-- Pilih Halaman Statis Lainnya --</option>
                @foreach ($statis_lainnya as $id => $nama)
                    <option value="{{ $id }}" @selected($menu['link'] == $id)>{{ str_replace(['[Pemerintah Desa]', '[Desa]'], [ucwords(setting('sebutan_pemerintah_desa')), ucwords(setting('sebutan_desa'))], $nama) }}</option>
                @endforeach
            </select>
            <select id="artikel_keuangan" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 6, 'link') }}" style="@if ($menu['link_tipe'] != 6) display:none; @endif">
                <option value="">-- Pilih Artikel Keuangan --</option>
                @foreach ($artikel_keuangan as $id => $data)
                    <option value="artikel/{{ $data['id'] }}" @selected($menu['link'] == "artikel/{$data['id']}")>{{ $data['judul'] }}</option>
                @endforeach
            </select>
            <select id="kelompok" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 7, 'link') }}" style="@if ($menu['link_tipe'] != 7) display:none; @endif">
                <option value="">Pilih Kelompok</option>
                @foreach ($kelompok as $kel)
                    <option value="data-kelompok/{{ $kel['id'] }}" @selected($menu['link'] == "data-kelompok/{$kel['id']}")>{{ $kel['nama'] . ' (' . $kel['master'] . ')' }}</option>
                @endforeach
            </select>
            <select id="lembaga" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 7, 'link') }}" style="@if ($menu['link_tipe'] != 7) display:none; @endif">
                <option value="">Pilih Lembaga</option>
                @foreach ($lembaga as $lem)
                    <option value="data-lembaga/{{ $lem['id'] }}" @selected($menu['link'] == "data-lembaga/{$lem['id']}")>{{ $lem['nama'] . ' (' . $lem['master'] . ')' }}</option>
                @endforeach
            </select>
            <select id="suplemen" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 9, 'link') }}" style="@if ($menu['link_tipe'] != 9) display:none; @endif">
                <option value="">Pilih Suplemen</option>
                @foreach ($suplemen as $sup)
                    <option value="data-suplemen/{{ $sup['id'] }}" @selected($menu['link'] == "data-suplemen/{$sup['id']}")>{{ $sup['nama'] }}</option>
                @endforeach
            </select>
            <select id="status_idm" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 10, 'link') }}" style="@if ($menu['link_tipe'] != 10) display:none; @endif">
                <option value="">Pilih Tahun</option>
                @foreach (tahun(2020) as $thn)
                    <option value="status-idm/{{ $thn }}" @selected($menu['link'] == "status-idm/{$thn}")>{{ $thn }}</option>
                @endforeach
            </select>
            <span id="eksternal" class="jenis_link" style="@if (!in_array($menu['link_tipe'], [88, 99])) display:none; @endif">
                <input name="{{ jecho(in_array($menu['link_tipe'], [88, 99]), true, 'link') }}" class="form-control input-sm" type="text" value="{{ $menu['link'] }}" />
                <span class="text-sm text-red">(misalnya: https://opendesa.id)</span>
            </span>
        </div>
        <div class="form-group">
            <label class="control-label" for="enabled">Status</label>
            <select name="enabled" class="form-control input-sm required">
                <option value="1" @selected($menu['enabled'] == 1)>Aktif</option>
                <option value="0" @selected($menu['enabled'] == 0)>Tidak Aktif</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm confirm"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>
@include('admin.layouts.components.form_modal_validasi')
<script>
    function ganti_jenis_link(elm) {
        let jenis = $(elm).val()
        $('#jenis_link').show();
        $('.jenis_link').hide();
        $('.jenis_link').removeAttr("name");
        $('.jenis_link').removeClass('required');
        // Select2 membuat span terpisah dan perlu ditangani khusus
        $('#validasi span.select2').hide();
        $('#eksternal > input').attr('name', '');

        if (jenis == '1') {
            $('#artikel_statis').show();
            $('#artikel_statis').attr('name', 'link');
            $('#artikel_statis').addClass('required');
        } else if (jenis == '2') {
            $('#statistik_penduduk').show();
            $('#statistik_penduduk').attr('name', 'link');
            $('#statistik_penduduk').addClass('required');
        } else if (jenis == '3') {
            $('#statistik_keluarga').show();
            $('#statistik_keluarga').attr('name', 'link');
            $('#statistik_keluarga').addClass('required');
        } else if (jenis == '4') {
            $('#statistik_program_bantuan').show();
            $('#statistik_program_bantuan').attr('name', 'link');
            $('#statistik_program_bantuan').addClass('required');
        } else if (jenis == '5') {
            $('#statis_lainnya').show();
            $('#statis_lainnya').attr('name', 'link');
            $('#statis_lainnya').addClass('required');
        } else if (jenis == '6') {
            $('#artikel_keuangan').show();
            $('#artikel_keuangan').attr('name', 'link');
            $('#artikel_keuangan').addClass('required');
        } else if (jenis == '7') {
            $('#kelompok').show();
            $('#kelompok').attr('name', 'link');
            $('#kelompok').addClass('required');
        } else if (jenis == '8') {
            $('#kategori_artikel').show();
            $('#kategori_artikel').attr('name', 'link');
            $('#kategori_artikel').addClass('required');
        } else if (jenis == '9') {
            $('#suplemen').show();
            $('#suplemen').attr('name', 'link');
            $('#suplemen').addClass('required');
        } else if (jenis == '10') {
            $('#status_idm').show();
            $('#status_idm').attr('name', 'link');
            $('#status_idm').addClass('required');
        } else if (jenis == '11') {
            $('#lembaga').show();
            $('#lembaga').attr('name', 'link');
            $('#lembaga').addClass('required');
        } else if (jenis == '12') {
            $('#statistik_kesehatan').show();
            $('#statistik_kesehatan').attr('name', 'link');
            $('#statistik_kesehatan').addClass('required');
        } else if (jenis == '88' || jenis == '99') {
            $('#eksternal').show();
            $('#eksternal > input').show();
            $('#eksternal > input').attr('name', 'link');
        } else {
            $('#jenis_link').hide();
        }

        $('select.jenis_link:visible').select2({
            width: '100%',
            dropdownAutoWidth: true
        });
    }

    $(document).ready(function() {

        $('#batal').click(function() {
            $('#link_tipe').change();
        });

        setTimeout(function() {
            $('#link_tipe').change();
        }, 500);
    });
</script>
