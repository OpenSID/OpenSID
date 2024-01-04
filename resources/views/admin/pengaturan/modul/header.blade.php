<div class="box box-info">
    <form id="validasi" action="{{ ci_route('modul.ubah_server') }}" method="POST" class="form-horizontal">
        <div class="box-body">
            <h4>Pengaturan Server</h4>
            <div class="form-group">
                <label class="col-sm-3 control-label">Penggunaan {{ config_item('nama_aplikasi') }} di {{ ucwords(setting('sebutan_desa')) }}</label>
                <div class="col-sm-9 col-lg-4">
                    <select class="form-control required input-sm" name="jenis_server" onchange="ubah_jenis_server($(this).val())">
                        <option value='' selected="selected">-- Pilih Penggunaan {{ config_item('nama_aplikasi') }} --</option>
                        <option value="1" @selected(setting('penggunaan_server') == '1')>
                            Offline saja di kantor desa
                        </option>
                        <option value="2" @selected(setting('penggunaan_server') == '2')>
                            Online saja di hosting
                        </option>
                        <option value="3" @selected(in_array(setting('penggunaan_server'), ['3', '5', '6']))>
                            Offline di kantor desa dan online di hosting
                        </option>
                        <option value="4" @selected(setting('penggunaan_server') == '4')>
                            Offline dan online di kantor desa
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="offline_online_hosting" style="@if (!in_array(setting('penggunaan_server'), ['3', '5', '6'])) display: none; @endif ">
                <label class="col-sm-3 control-label">Server ini digunakan sebagai</label>
                <div class="col-sm-9 col-lg-4">
                    <select class="form-control input-sm" name="server_mana" onchange="ubah_server($(this).val())">
                        <option value='' selected="selected">-- Pilih Server Ini --</option>
                        <option value="5" @selected(setting('penggunaan_server') == '5')>
                            Offline admin saja di kantor desa
                        </option>
                        <option value="6" @selected(setting('penggunaan_server') == '6')>
                            Online web publik saja di hosting
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="offline_ada_hosting" style="@if (setting('penggunaan_server') != '5') display: none; @endif">
                <label class="col-sm-3 control-label">Akses web pada server offline ini</label>
                <div class="col-sm-6 col-lg-4">
                    <select class="form-control input-sm" name="offline_mode">
                        <option value='' selected="selected">-- Pilih Akses Web --</option>
                        <option value="1" @selected(setting('penggunaan_server') == '5' && setting('offline_mode') == '1')>
                            Web bisa diakses petugas web
                        </option>
                        <option value="2" @selected(setting('penggunaan_server') == '5' && setting('offline_mode') == '2')>
                            Web non-aktif sama sekali
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="offline_saja" style="@if (setting('penggunaan_server') != '1') display: none; @endif ">
                <label class="col-sm-3 control-label">Akses web pada server offline ini</label>
                <div class="col-sm-9 col-lg-4">
                    <select class="form-control input-sm" name="offline_mode_saja">
                        <option value='' selected="selected">-- Pilih Akses Web --</option>
                        @foreach (\App\Enums\OfflineModeEnum::all() as $key => $item)
                            <option value="{{ $key }}" @selected(setting('penggunaan_server') == '1' && setting('offline_mode') == $key)>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @if (can('u'))
            <div class="box-footer">
                <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
                <button type='submit' class='btn btn-social btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
            </div>
        @endif
    </form>
    @if (can('u') && setting('penggunaan_server') == '6')
        <div class="box-body">
            <div class="alert alert-info">
                <p>Server ini hanya digunakan untuk menampilkan data bagi publik. Secara default, semua modul dinon-aktifkan kecuali menu Pengaturan dan Admin Web. Pengelolaan data penduduk dan lain-lain dilakukan di server terpisah, secara offline di Kantor Desa. Untuk memutakhirkan data di
                    server ini, unggah data secara berkala dari server yang digunakan untuk pengelolaan data.</p>
                <p>Sebaiknya data di server ini diacak atau disensor untuk menjaga privasi data penduduk dan data lain.</p>
            </div>
            <a href="#" data-title="Acak Data" class="btn btn-social btn-danger btn-sm" data-toggle="modal" data-target="#confirm-acak"><i class='fa fa-trash-o'></i>Acak Data</a>
            <a
                href="{{ ci_route('database.mutakhirkan_data_server') }}"
                title="Sinkronkan Data"
                data-remote="false"
                data-toggle="modal"
                data-target="#modalBox"
                data-title="Sinkronkan Data"
                class="btn btn-social btn-info btn-sm"
            ><i class="fa fa-refresh"></i>Impor Data Mutakhir</a>
        </div>
    @endif
</div>
@push('scripts')
    <script type="text/javascript">
        function ubah_jenis_server(jenis_server) {
            $('#offline_saja select').val('');
            if (jenis_server == 3) {
                $('#offline_saja').hide();
                $('#offline_saja select').removeClass('required');
                $('#offline_online_hosting select').val('');
                $('#offline_online_hosting select').addClass('required');
                $('#offline_online_hosting').show();
            } else {
                $('#offline_online_hosting select').val('');
                $('#offline_online_hosting select').change();
                $('#offline_online_hosting').hide();
                $('#offline_online_hosting select').removeClass('required');
                $('#offline_saja select').removeClass('required');
                $('#offline_saja').hide();
                if (jenis_server == 1) {
                    $('#offline_saja select').addClass('required');
                    $('#offline_saja').show();
                }
            }
        }

        function ubah_server(server) {
            $('#offline_saja select').val('');
            $('#offline_ada_hosting select').val('');

            if (server == 5) {
                $('#offline_ada_hosting select').addClass('required');
                $('#offline_ada_hosting').show();
            } else {
                $('#offline_ada_hosting select').removeClass('required');
                $('#offline_ada_hosting').hide();
            }
        }
    </script>
@endpush
