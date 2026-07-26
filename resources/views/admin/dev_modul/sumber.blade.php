<div class="tab-pane active">
    <div class="callout callout-warning">
        <h4><i class="fa fa-flask"></i> Mode Pengembangan — bukan bagian rilis</h4>
        <p style="margin-bottom:0">
            Bursa paket lokal adalah <strong>gudang ZIP paket</strong> (seperti Layanan). Isi lewat
            <strong>Daftarkan paket</strong> di bawah, lalu pilih sumber <em>Bursa paket lokal</em> agar seluruh
            tab (Paket Tersedia, Form Pendaftaran, Riwayat Pemesanan) beroperasi atasnya — untuk menguji alur
            ambil/lepas (get/release) tanpa server Layanan.
        </p>
    </div>

    {!! form_open($form_action, 'id="form-sumber" class="form-horizontal"') !!}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Sumber paket</h3>
            <div class="pull-right">
                Aktif:
                @if ($lokal)
                    <span class="label label-warning">Bursa paket lokal</span>
                @else
                    <span class="label label-default">Layanan (server nyata)</span>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Sumber</label>
                <div class="col-sm-9">
                    <div class="radio" style="margin-top:5px">
                        <label>
                            <input type="radio" name="lokal" value="layanan" {{ $lokal ? '' : 'checked' }}>
                            Layanan (server nyata: <code>{{ $server_layanan !== '' ? $server_layanan : '(belum diatur)' }}</code>)
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="lokal" value="lokal" {{ $lokal ? 'checked' : '' }}>
                            Bursa paket lokal (gudang ZIP)
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @if (can('u'))
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Terapkan sumber</button>
            </div>
        @endif
    </div>
    {!! form_close() !!}

    @if (can('u'))
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Data langganan pelanggan (simulasi Layanan)</h3>
                <div class="pull-right">
                    Status:
                    @if ($langganan_aktif)
                        <span class="label label-success">Terisi</span>
                    @else
                        <span class="label label-default">Kosong</span>
                    @endif
                </div>
            </div>
            <div class="box-body">
                <p class="help-block" style="margin-bottom:10px">
                    Mengisi cache <code>status_langganan</code> dengan data pemesanan simulasi (Premium + Hosting)
                    yang dibangun dari identitas desa ini — cache yang sama yang dibaca halaman
                    <a href="{{ $link_pelanggan }}"><strong>Info Desa &raquo; Pelanggan</strong></a>. Dengan begitu
                    halaman menampilkan status langganan <em>seolah datang dari Layanan</em>, tanpa server Layanan nyata.
                </p>
            </div>
            <div class="box-footer">
                {!! form_open($form_langganan, 'style="display:inline"') !!}
                <button type="submit" class="btn btn-social btn-warning btn-sm">
                    <i class="fa fa-magic"></i> Isi data langganan simulasi
                </button>
                {!! form_close() !!}
                @if ($langganan_aktif)
                    {!! form_open($form_langganan_kosong, 'style="display:inline"') !!}
                    <button type="submit" class="btn btn-social btn-default btn-sm">
                        <i class="fa fa-eraser"></i> Kosongkan
                    </button>
                    {!! form_close() !!}
                @endif
            </div>
        </div>

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan paket dari URL repo</h3>
            </div>
            {!! form_open($form_daftar, 'class="form-horizontal" id="form-daftar-url"') !!}
            <div class="box-body">
                <p class="help-block">
                    Mengunduh ZIP repo (mis. GitHub) ke bursa paket (<code>storage/app/dev-marketplace</code>) —
                    seperti Layanan menyimpan ZIP paket. Repo privat memakai <code>gh</code> (login GitHub Anda).
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">URL repo</label>
                    <div class="col-sm-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">https://github.com/</span>
                            <input type="text" name="url" class="form-control" required
                                   placeholder="OpenSID/modul-anjungan">
                        </div>
                        <small class="text-muted">Cukup <code>owner/repo</code> (atau tempel URL lengkap / bentuk <code>owner/repo/tree/&lt;ref&gt;</code>).</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ref (opsional)</label>
                    <div class="col-sm-4">
                        <input type="text" name="ref" class="form-control input-sm" placeholder="cabang/tag (mis. rilis-dev)">
                        <small class="text-muted">Kosong = cabang utama repo. Isi tag/cabang untuk versi spesifik.</small>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-success btn-sm pull-right"><i class="fa fa-download"></i> Unduh &amp; daftarkan</button>
            </div>
            {!! form_close() !!}
        </div>

        <div class="box box-default collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan dari folder lokal (opsional)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! form_open($form_daftar_lokal, 'class="form-horizontal" id="form-daftar-lokal"') !!}
            <div class="box-body">
                <p class="help-block">
                    Snapshot <em>working-tree</em> folder paket lokal (berisi <code>module.json</code>, termasuk perubahan
                    belum-commit) ke gudang. Berguna saat Anda sendiri sedang menggarap paket itu.
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Paket terpasang</label>
                    <div class="col-sm-6">
                        <select id="kandidat" class="form-control input-sm">
                            <option value="">-- pilih paket terpasang --</option>
                            @foreach ($kandidat as $k)
                                <option value="{{ $k['path'] }}">{{ $k['name'] }} ({{ $k['path'] }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Atau isi path folder paket di bawah.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Path folder paket</label>
                    <div class="col-sm-6">
                        <input type="text" name="path" id="path-daftar" class="form-control input-sm"
                               placeholder="/path/ke/modul-anjungan">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Snapshot &amp; daftarkan</button>
            </div>
            {!! form_close() !!}
        </div>
    @endif

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Isi bursa paket lokal (gudang ZIP)</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Versi</th>
                        <th>Sumber</th>
                        <th>Ref</th>
                        <th>Status</th>
                        <th>Didaftarkan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paket_repo as $m)
                        <tr>
                            <td><strong>{{ $m['name'] }}</strong></td>
                            <td>{{ $m['version'] !== '' ? $m['version'] : '-' }}</td>
                            <td><code>{{ $m['sumber'] !== '' ? $m['sumber'] : '-' }}</code></td>
                            <td>{{ $m['ref'] !== '' ? $m['ref'] : '-' }}</td>
                            <td>
                                @if ($m['installed'])
                                    <span class="label label-success">terpasang</span>
                                @else
                                    <span class="label label-default">belum</span>
                                @endif
                            </td>
                            <td>{{ $m['waktu'] !== '' ? $m['waktu'] : '-' }}</td>
                            <td class="text-right">
                                @if (can('u'))
                                    {!! form_open($form_batal, 'style="display:inline" onsubmit="return confirm(\'Keluarkan paket ' . $m['name'] . ' dari bursa paket lokal?\')"') !!}
                                    <button type="submit" name="name" value="{{ $m['name'] }}" class="btn btn-danger btn-xs">
                                        <i class="fa fa-times"></i> Keluarkan
                                    </button>
                                    {!! form_close() !!}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="alert alert-warning" style="margin:10px">
                                    Bursa paket lokal kosong. Daftarkan paket dari URL repo di atas — mis.
                                    <code>https://github.com/OpenSID/modul-anjungan</code>.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <small class="text-muted">
                Setelah mode lokal aktif: pasang lewat <strong>Paket Tersedia</strong> atau <strong>Form Pendaftaran</strong>,
                lepas lewat <strong>Paket Terpasang → Hapus</strong>; jejaknya muncul di <strong>Riwayat Pemesanan</strong>.
            </small>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            $('#kandidat').on('change', function () {
                if ($(this).val()) {
                    $('#path-daftar').val($(this).val());
                }
            });
        });
    </script>
@endpush
