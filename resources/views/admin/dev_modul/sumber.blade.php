<div class="tab-pane active">
    <div class="callout callout-warning">
        <h4><i class="fa fa-flask"></i> Mode Pengembangan — bukan bagian rilis</h4>
        <p style="margin-bottom:0">
            Pilih <strong>dari mana</strong> halaman Paket Tambahan mengambil modul. Mode <em>Lokal</em> menjadikan
            seluruh tab (Paket Tersedia, Form Pendaftaran, Riwayat Pemesanan) beroperasi atas repo lokal sebagai
            simulasi Layanan — untuk menguji alur ambil/lepas (get/release) tanpa server Layanan.
        </p>
    </div>

    {!! form_open($form_action, 'id="form-sumber" class="form-horizontal"') !!}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Sumber modul</h3>
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
                <label class="col-sm-3 control-label">Basis repo</label>
                <div class="col-sm-9">
                    <p class="form-control-static">
                        <code>{{ $repo_base !== '' ? $repo_base : '(module_dev_repo_base belum diatur — set di config)' }}</code>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Strategi (lokal)</label>
                <div class="col-sm-9">
                    <label class="radio-inline">
                        <input type="radio" name="strategy" value="working-tree" {{ $opsi['strategy'] !== 'git-archive' ? 'checked' : '' }}>
                        <em>working-tree</em> (berkas apa adanya, termasuk belum-commit)
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="strategy" value="git-archive" {{ $opsi['strategy'] === 'git-archive' ? 'checked' : '' }}>
                        <em>git-archive</em> (pohon tercommit pada ref)
                    </label>
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

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Modul di repo lokal</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Modul</th>
                        <th>Folder</th>
                        <th>Git</th>
                        <th>Versi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modul_repo as $m)
                        <tr>
                            <td><strong>{{ $m['name'] }}</strong></td>
                            <td><code>{{ $m['folder'] }}</code></td>
                            <td>
                                @if ($m['is_git'])
                                    <span class="label label-info">git {{ $m['head'] }}</span>
                                @else
                                    <span class="label label-default">bukan repo</span>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="alert alert-warning" style="margin:10px">
                                    Tak ada repo modul di bawah <code>module_dev_repo_base</code>. Atur config ke direktori
                                    induk berisi checkout repo modul (mis. <code>.../modul-anjungan</code>).
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
        });
    </script>
@endpush
