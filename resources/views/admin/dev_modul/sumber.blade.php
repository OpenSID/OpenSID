<div class="tab-pane active">
    <div class="callout callout-warning">
        <h4><i class="fa fa-flask"></i> Mode Pengembangan — bukan bagian rilis</h4>
        <p style="margin-bottom:0">
            Pilih <strong>dari mana</strong> halaman Paket Tambahan mengambil paket. Mode <em>Lokal</em> menjadikan
            seluruh tab (Paket Tersedia, Form Pendaftaran, Riwayat Pemesanan) beroperasi atas marketplace repo lokal
            sebagai simulasi Layanan — untuk menguji alur ambil/lepas (get/release) tanpa server Layanan.
        </p>
    </div>

    {!! form_open($form_action, 'id="form-sumber" class="form-horizontal"') !!}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Sumber paket</h3>
            <div class="pull-right">
                Aktif:
                @if ($lokal)
                    <span class="label label-warning">Marketplace lokal</span>
                @else
                    <span class="label label-default">Layanan (server nyata)</span>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Sumber</label>
                <div class="col-sm-9">
                    <label class="radio-inline">
                        <input type="radio" name="lokal" value="layanan" {{ $lokal ? '' : 'checked' }}>
                        Layanan (server nyata: <code>{{ $server_layanan !== '' ? $server_layanan : '(belum diatur)' }}</code>)
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="lokal" value="lokal" {{ $lokal ? 'checked' : '' }}>
                        Marketplace repo lokal
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Strategi (paket repo hidup)</label>
                <div class="col-sm-9">
                    <label class="radio-inline">
                        <input type="radio" name="strategy" value="working-tree" {{ $opsi['strategy'] !== 'git-archive' ? 'checked' : '' }}>
                        <em>working-tree</em> (berkas apa adanya, termasuk belum-commit)
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="strategy" value="git-archive" {{ $opsi['strategy'] === 'git-archive' ? 'checked' : '' }}>
                        <em>git-archive</em> (pohon tercommit pada ref)
                    </label>
                    <p class="help-block" style="margin-bottom:0">
                        Berlaku untuk paket repo hidup di bawah <code>module_dev_repo_base</code>. Paket terdaftar
                        (gudang) selalu dibungkus <em>working-tree</em> dari snapshot-nya.
                    </p>
                </div>
            </div>

            <div id="opsi-git">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ref git</label>
                    <div class="col-sm-4">
                        <input type="text" name="ref" class="form-control input-sm" value="{{ $opsi['ref'] }}"
                               placeholder="HEAD / origin/main / v1.2.0">
                        <small class="text-muted">Versi terbaru remote: <code>origin/&lt;cabang&gt;</code> + centang di bawah.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">&nbsp;</label>
                    <div class="col-sm-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="fetch" value="1" {{ $opsi['fetch'] ? 'checked' : '' }}>
                                Tarik pembaruan remote dulu (<code>git fetch</code>)
                            </label>
                        </div>
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
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan paket ke marketplace lokal</h3>
            </div>
            {!! form_open($form_daftar, 'class="form-horizontal" id="form-daftar"') !!}
            <div class="box-body">
                <p class="help-block">
                    Menyalin snapshot paket ke gudang marketplace (<code>storage/app/dev-marketplace</code>) supaya
                    tersedia untuk diajukan (get) &amp; tetap ada walau dihapus (release).
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Paket (kandidat)</label>
                    <div class="col-sm-6">
                        <select id="kandidat" class="form-control input-sm">
                            <option value="">-- pilih paket terpasang / repo --</option>
                            @foreach ($kandidat as $k)
                                <option value="{{ $k['path'] }}">{{ $k['name'] }} — {{ $k['asal'] === 'terpasang' ? 'terpasang' : 'repo' }} ({{ $k['path'] }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Atau isi path folder paket (berisi <code>module.json</code>) di bawah.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Path paket</label>
                    <div class="col-sm-6">
                        <input type="text" name="path" id="path-daftar" class="form-control input-sm"
                               placeholder="/path/ke/modul-anjungan">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Daftarkan</button>
            </div>
            {!! form_close() !!}
        </div>
    @endif

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Isi marketplace lokal</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Asal</th>
                        <th>Git</th>
                        <th>Versi</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paket_repo as $m)
                        <tr>
                            <td><strong>{{ $m['name'] }}</strong> <code>{{ $m['folder'] }}</code></td>
                            <td>
                                @if ($m['terdaftar'])
                                    <span class="label label-success">terdaftar (gudang)</span>
                                @else
                                    <span class="label label-info">repo hidup</span>
                                @endif
                            </td>
                            <td>
                                @if ($m['is_git'])
                                    <span class="label label-info">git {{ $m['head'] }}</span>
                                @else
                                    <span class="label label-default">—</span>
                                @endif
                            </td>
                            <td>{{ $m['version'] !== '' ? $m['version'] : '-' }}</td>
                            <td>
                                @if ($m['installed'])
                                    <span class="label label-success">terpasang</span>
                                @else
                                    <span class="label label-default">belum</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($m['terdaftar'] && can('u'))
                                    {!! form_open($form_batal, 'style="display:inline" onsubmit="return confirm(\'Keluarkan paket ' . $m['name'] . ' dari marketplace lokal?\')"') !!}
                                    <button type="submit" name="name" value="{{ $m['name'] }}" class="btn btn-danger btn-xs">
                                        <i class="fa fa-times"></i> Keluarkan
                                    </button>
                                    {!! form_close() !!}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="alert alert-warning" style="margin:10px">
                                    Marketplace lokal kosong. Daftarkan paket di atas (mis. <code>Anjungan</code> dari
                                    paket terpasang), atau atur <code>module_dev_repo_base</code> ke direktori berisi
                                    checkout repo paket.
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
            function toggleOpsiGit() {
                $('#opsi-git').toggle($('input[name="strategy"]:checked').val() === 'git-archive');
            }
            $('input[name="strategy"]').on('change', toggleOpsiGit);
            toggleOpsiGit();

            $('#kandidat').on('change', function () {
                if ($(this).val()) {
                    $('#path-daftar').val($(this).val());
                }
            });
        });
    </script>
@endpush
