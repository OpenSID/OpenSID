@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Menu
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('anjungan_menu') }}">Menu</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('anjungan_menu') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Menu</a>
        </div>
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Nama</label>
                <div class="col-sm-9">
                    <input name="nama" class="form-control input-sm required nomor_sk" maxlength="50" type="text" value="{{ $menu['nama'] }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Icon</label>
                <div class="col-sm-9">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control required" id="file_path" name="icon">
                        <input type="file" class="hidden" id="file" name="icon" accept=".gif,.jpg,.jpeg,.png">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i>&nbsp;Browse</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="link">Jenis Link</label>
                <div class="col-sm-9">
                    <select class="form-control input-sm required" id="link_tipe" name="link_tipe">
                        <option option value="">-- Pilih Jenis Link --</option>
                        @foreach ($link_tipe as $id => $nama)
                            <option value="{{ $id }}" {{ selected($menu['link_tipe'], $id) }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group" id="jenis_link" style="{{ !$menu['link_tipe'] && (print 'display:none;') }}">
                <label class="col-sm-3 control-label" for="link">Link</label>
                <div class="col-sm-9">
                    <select id="artikel_statis" class="form-control input-sm jenis_link select2" name="{{ jecho($menu['link_tipe'], 1, 'link') }}" style="{{ $menu['link_tipe'] != 1 && (print 'display:none;') }}">
                        <option value="">-- Pilih Artikel Statis --</option>
                        @foreach ($artikel_statis as $data)
                            <option value="artikel/{{ $data['id'] }}" {{ selected($menu['link'], "artikel/{$data['id']}") }}>{{ $data['judul'] }}</option>
                        @endforeach
                    </select>
                    <select id="kategori_artikel" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 8, 'link') }}" style="{{ $menu['link_tipe'] != 8 && (print 'display:none;') }}">
                        <option value="">-- Pilih Kategori Artikel --</option>
                        @foreach ($kategori_artikel as $data)
                            <option value="kategori/{{ $data['slug'] }}" {{ selected($menu['link'], "kategori/{$data['slug']}") }}>{{ $data['kategori'] }}</option>
                        @endforeach
                    </select>
                    <select id="statistik_penduduk" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 2, 'link') }}" style="{{ $menu['link_tipe'] != 2 && (print 'display:none;') }}">
                        <option value="">-- Pilih Statistik Penduduk --</option>
                        @foreach ($statistik_penduduk as $id => $nama)
                            <option value="{{ "statistik/{$id}" }}" {{ selected($menu['link'], "statistik/{$id}") }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    <select id="statistik_keluarga" class="form-control jenis_link input-sm" name="{{ jecho($menu['link_tipe'], 3, 'link') }}" style="{{ $menu['link_tipe'] != 3 && (print 'display:none;') }}">
                        <option value="">-- Pilih Statistik Keluarga --</option>
                        @foreach ($statistik_keluarga as $id => $nama)
                            <option value="{{ "statistik/{$id}" }}" {{ selected($menu['link'], "statistik/{$id}") }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    <select id="statistik_program_bantuan" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 4, 'link') }}" style="{{ $menu['link_tipe'] != 4 && (print 'display:none;') }}">
                        <option value="">-- Pilih Statistik Program Bantuan --</option>
                        @foreach ($statistik_kategori_bantuan as $id => $nama)
                            <option value="{{ "statistik/{$id}" }}" {{ selected($menu['link'], "statistik/{$id}") }}>{{ $nama }}</option>
                        @endforeach
                        @foreach ($statistik_program_bantuan as $nama)
                            <option value="{{ "statistik/{$nama['lap']}" }}" {{ selected($menu['link'], "statistik/{$nama['lap']}") }}>{{ $nama['nama'] }}</option>
                        @endforeach
                    </select>
                    <select id="statis_lainnya" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 5, 'link') }}" style="{{ $menu['link_tipe'] != 5 && (print 'display:none;') }}">
                        <option value="">-- Pilih Halaman Statis Lainnya --</option>
                        @foreach ($statis_lainnya as $id => $nama)
                            <option value="{{ $id }}" {{ selected($menu['link'], $id) }}>{{ str_replace('[Desa]', ucwords(setting('sebutan_desa')), $nama) }}</option>
                        @endforeach
                    </select>
                    <select id="artikel_keuangan" class="form-control input-sm jenis_link" name="{{ jecho($menu['link_tipe'], 6, 'link') }}" style="{{ $menu['link_tipe'] != 6 && (print 'display:none;') }}">
                        <option value="">-- Pilih Artikel Keuangan --</option>
                        @foreach ($artikel_keuangan as $data)
                            <option value="{{ $data['id'] }}" {{ selected($menu['link'], $data['id']) }}>{{ $data['judul'] }}</option>
                        @endforeach
                    </select>
                    <select id="kelompok" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 7, 'link') }}" style="{{ $menu['link_tipe'] != 7 && (print 'display:none;') }}">
                        <option value="">Pilih Kelompok</option>
                        @foreach ($kelompok as $kel)
                            <option value="{{ "data-kelompok/{$kel['id']}" }}" {{ selected($menu['link'], "data-kelompok/{$kel['id']}") }}>{{ $kel['nama'] . ' (' . $kel['master'] . ')' }}</option>
                        @endforeach
                    </select>
                    <select id="lembaga" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 7, 'link') }}" style="{{ $menu['link_tipe'] != 7 && (print 'display:none;') }}">
                        <option value="">Pilih Lembaga</option>
                        @foreach ($lembaga as $lem)
                            <option value="{{ "data-lembaga/{$lem['id']}" }}" {{ selected($menu['link'], "data-lembaga/{$lem['id']}") }}>{{ $lem['nama'] . ' (' . $lem['master'] . ')' }}</option>
                        @endforeach
                    </select>
                    <select id="suplemen" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 9, 'link') }}" style="{{ $menu['link_tipe'] != 9 && (print 'display:none;') }}">
                        <option value="">Pilih Suplemen</option>
                        @foreach ($suplemen as $sup)
                            <option value="{{ "data-suplemen/{$sup['id']}" }}" {{ selected($menu['link'], "data-suplemen/{$sup['id']}") }}>{{ $sup['nama'] }}</option>
                        @endforeach
                    </select>
                    <select id="status_idm" class="form-control input-sm jenis_link required" name="{{ jecho($menu['link_tipe'], 10, 'link') }}" style="{{ $menu['link_tipe'] != 10 && (print 'display:none;') }}">
                        <option value="">Pilih Tahun</option>
                        @foreach (tahun(2020) as $thn)
                            <option value="{{ "status-idm/{$thn}" }}" {{ selected($menu['link'], "status-idm/{$thn}") }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $('#link_tipe').on('change', function() {
            var jenis = this.value;
            $('#jenis_link').show();
            $('.jenis_link').hide();
            $('.jenis_link').removeAttr("name");
            $('.jenis_link').removeClass('required');
            // Select2 membuat span terpisah dan perlu ditangani khusus
            $('span.select2').hide();
            $('#eksternal > input').attr('name', '');
            if (jenis == '1') {
                $('#artikel_statis').show();
                $('#artikel_statis').attr('name', 'link');
                $('#artikel_statis').addClass('required');
                $('#artikel_statis').select2({
                    width: '100%',
                    dropdownAutoWidth: true
                });
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
            } else {
                $('#jenis_link').hide();
            }
        });
        $(document).ready(function() {
            $('#batal').click(function() {
                $('#link_tipe').change();
            });
            setTimeout(function() {
                $('#link_tipe').change();
            }, 500);
        });
    </script>
@endpush
