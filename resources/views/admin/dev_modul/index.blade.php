@extends('admin.layouts.index')

@section('title')
    <h1>Sumber Modul (Pengembangan)</h1>
@endsection

@section('breadcrumb')
    <li class="active"><a href="{{ ci_route('dev_modul.index') }}">Sumber Modul (Pengembangan)</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="callout callout-warning">
        <h4><i class="fa fa-flask"></i> Mode Pengembangan</h4>
        <p>
            Panel ini hanya aktif di lingkungan <code>development</code> dan tidak ikut ke rilis. Ia memasang/memperbarui
            add-on langsung dari repo lokal (simulasi Layanan) atau dari server Layanan nyata — untuk memverifikasi
            modul & pembaruannya tanpa harus melalui Layanan.
        </p>
        <p style="margin-bottom:0">
            Basis repo (<code>module_dev_repo_base</code>):
            <strong>{{ $repo_base !== '' ? $repo_base : '(belum diatur — set di config)' }}</strong>
        </p>
    </div>

    {!! form_open($form_action, 'id="form-dev-modul"') !!}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Sumber &amp; strategi</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label">Sumber</label>
                <div>
                    <label class="radio-inline">
                        <input type="radio" name="source" value="working-tree" {{ $default_strategy !== 'git-archive' ? 'checked' : '' }}>
                        Repo lokal — <em>working-tree</em> (berkas apa adanya, termasuk belum-commit)
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="source" value="git-archive" {{ $default_strategy === 'git-archive' ? 'checked' : '' }}>
                        Repo lokal — <em>git-archive</em> (pohon tercommit pada ref)
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="source" value="layanan">
                        Layanan (server nyata)
                    </label>
                </div>
            </div>

            <div class="row" id="opsi-git">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Ref git (git-archive)</label>
                        <input type="text" name="ref" class="form-control" value="{{ $default_ref }}"
                               placeholder="HEAD / origin/main / v1.2.0">
                        <small class="text-muted">Untuk versi terbaru remote, gunakan <code>origin/&lt;cabang&gt;</code> + centang di bawah.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="fetch" value="1" {{ $default_fetch ? 'checked' : '' }}>
                                Tarik pembaruan remote dulu (<code>git fetch</code>)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
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
                        <th class="text-right">Aksi</th>
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
                            <td class="text-right">
                                <button type="submit" name="name" value="{{ $m['name'] }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-download"></i> {{ $m['installed'] ? 'Perbarui' : 'Pasang' }}
                                </button>
                                @if ($m['installed'])
                                    <button type="submit" name="name" value="{{ $m['name'] }}"
                                            formaction="{{ $form_hapus }}" class="btn btn-danger btn-sm btn-hapus">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
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
                Sumber Layanan mengambil modul berdasarkan katalog server (butuh Layanan terjangkau + token).
            </small>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(function () {
            function toggleOpsiGit() {
                var v = $('input[name="source"]:checked').val();
                $('#opsi-git').toggle(v === 'git-archive');
            }
            $('input[name="source"]').on('change', toggleOpsiGit);
            toggleOpsiGit();

            $('.btn-hapus').on('click', function (e) {
                if (! confirm('Hapus modul ' + $(this).val() + '? Migrasi down akan dijalankan.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
