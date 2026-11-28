<div class="tab-pane {{ $act_tab == 3 ? 'active' : '' }}">
    <div class="row">
        <div class="col-md-10">

@if ($step === 'scan')
{{-- ── Halaman scan — tampilkan kandidat untuk dihapus ─────────────────── --}}

<p class="text-muted" style="font-size:12px; margin-bottom:12px;">
    <i class="fa fa-clock-o"></i>
    Discan pada {{ $scannedAt->format('d M Y, H:i:s') }}
    &mdash;
    <a href="{{ route('database.bersih_folder') }}?rescan=1"><i class="fa fa-refresh"></i> Scan ulang</a>
    (mengembalikan semua pilihan ke default)
</p>

@if (! empty($tersimpan))
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i> Pilihan disimpan. File yang tidak dicentang akan
    <strong>dipertahankan</strong> — pada kunjungan berikutnya maupun saat proses Acak Data berjalan.
</div>
@endif

@if (empty($groups))

<div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    <strong>Folder desa sudah bersih.</strong>
    Tidak ada file yang tidak dibutuhkan atau tidak dirujuk oleh database.
</div>

@else

@php
    $totalFiles = array_sum(array_map(fn($g) => $g->fileCount, $groups));
    $totalBytes = array_sum(array_map(fn($g) => $g->totalSize, $groups));
@endphp

<div class="alert alert-warning">
    <i class="fa fa-exclamation-triangle"></i>
    <strong>Peringatan:</strong> File yang dihapus <strong>tidak dapat dipulihkan</strong>.
    Pastikan <strong>folder desa</strong> sudah dibackup sebelum melanjutkan.
</div>

<p>
    Ditemukan <strong id="summary-count">{{ number_format($totalFiles) }}</strong> file
    (<strong id="summary-size">{{ \App\Services\FolderDesaCleaner\ScanGroup::formatBytes($totalBytes) }}</strong>)
    yang dapat dihapus dari folder desa.
    Hilangkan centang file yang ingin <strong>dipertahankan</strong>, lalu <strong>Simpan pilihan</strong>
    atau <strong>Hapus</strong>. Pilihan tersimpan tetap berlaku saat kembali ke tab ini dan
    dihormati oleh proses <strong>Acak Data</strong>.
</p>

<form id="form-bersih" action="{{ route('database.bersih_folder.hapus') }}" method="POST">
    {{-- Token CSRF (sidcsrf) disisipkan otomatis oleh anti-csrf.js saat submit. --}}

    @foreach ($groups as $group)
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-8">
                    <h4 class="box-title" style="margin:0;">
                        <code>{{ $group->folderLabel }}</code>
                        &nbsp;
                        <span class="label label-default">{{ number_format($group->fileCount) }} file</span>
                        <span class="label label-default">{{ $group->formattedSize() }}</span>
                    </h4>
                    <small class="text-muted">{{ $group->description }}</small>
                    <div style="margin-top:4px;">
                        <span class="label label-danger group-sel-badge"
                              id="selbadge-{{ $group->key }}"
                              data-group="{{ $group->key }}"
                              data-total-files="{{ $group->fileCount }}"
                              data-total-bytes="{{ $group->totalSize }}"
                              style="display:inline-block; font-size:12px;">
                            <i class="fa fa-trash"></i>
                            <span class="sel-files">{{ number_format($group->fileCount) }}</span>/{{ number_format($group->fileCount) }} file
                            &middot; <span class="sel-size">{{ $group->formattedSize() }}</span> dipilih
                        </span>
                    </div>
                </div>
                <div class="col-sm-4 text-right" style="padding-top:4px; white-space:nowrap;">
                    {{-- Master checkbox tri-state: mengendalikan/merefleksikan centang
                         tiap file di folder (checked / unchecked / indeterminate). Berlaku
                         sama untuk semua folder, termasuk folder tak dikenal. Pengiriman
                         ditangani submit handler: terpilih penuh → satu bulk_keys[]
                         (menghapus seluruh isi folder), sebagian → path per-file. --}}
                    <label class="checkbox-inline master-checkbox-label" style="font-weight:normal;"
                           title="Pilih / batalkan semua file di folder ini">
                        <input type="checkbox"
                               class="master-checkbox"
                               data-group="{{ $group->key }}"
                               data-files="{{ $group->fileCount }}"
                               data-bytes="{{ $group->totalSize }}"
                               checked>
                        Pilih semua
                    </label>
                </div>
            </div>
            <div class="progress progress-xs active" style="margin:8px 0 0; height:6px;">
                <div class="progress-bar progress-bar-danger group-sel-bar"
                     id="selbar-{{ $group->key }}"
                     role="progressbar"
                     style="width:100%;"></div>
            </div>
        </div>

        @if ($group->fileCount > 0)
        <div class="box-body" style="padding: 0;">
            @php $collapsed = $group->fileCount > 50; @endphp
            @if ($collapsed)
            <div class="box-body" style="padding:8px 15px;">
                <a href="#" class="toggle-filelist" data-group="{{ $group->key }}">
                    <i class="fa fa-chevron-down"></i> Tampilkan {{ number_format($group->fileCount) }} file
                </a>
            </div>
            @endif
            <div class="file-list {{ $collapsed ? 'hidden' : '' }}" id="filelist-{{ $group->key }}"
                 style="max-height:320px; overflow-y:auto;">
                <table class="table table-condensed table-hover" style="margin:0;">
                    <tbody>
                        @foreach ($group->files as $rel => $meta)
                        <tr>
                            <td style="width:24px; padding:4px 8px;">
                                <input type="checkbox"
                                       class="file-checkbox group-{{ $group->key }}"
                                       value="{{ $rel }}"
                                       @checked(! in_array($rel, $keep ?? [], true))
                                       data-bytes="{{ $meta['size'] }}">
                            </td>
                            <td style="padding:4px 6px;">
                                <code style="font-size:12px;">{{ basename($rel) }}</code>
                            </td>
                            <td style="width:80px; text-align:right; padding:4px 8px; color:#888; font-size:12px;">
                                {{ \App\Services\FolderDesaCleaner\ScanGroup::formatBytes($meta['size']) }}
                            </td>
                            <td style="width:90px; text-align:right; padding:4px 8px; color:#aaa; font-size:11px;">
                                {{ $meta['mtime'] ? date('Y-m-d', $meta['mtime']) : '' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    <div class="form-group" style="margin-top: 16px;">
        <button type="submit"
                class="btn btn-primary btn-social"
                formaction="{{ route('database.bersih_folder.simpan') }}">
            <i class="fa fa-save"></i> Simpan pilihan
        </button>
        &nbsp;
        <button type="submit"
                id="btn-hapus"
                class="btn btn-danger btn-social">
            <i class="fa fa-trash"></i>
            Hapus <span id="btn-count">{{ number_format($totalFiles) }}</span> file terpilih
            (<span id="btn-size">{{ \App\Services\FolderDesaCleaner\ScanGroup::formatBytes($totalBytes) }}</span>)
        </button>
        &nbsp;
        <a href="{{ route('database.bersih_folder') }}?rescan=1" class="btn btn-default btn-social">
            <i class="fa fa-refresh"></i> Scan ulang
        </a>
    </div>

</form>

<script>
(function () {
    // Menandai apakah pilihan berubah tapi belum disimpan.
    var dirty = false;

    // ── Live counter ─────────────────────────────────────────────────────────
    function formatBytes(b) {
        if (b >= 1073741824) return (b / 1073741824).toFixed(1) + ' GB';
        if (b >= 1048576)    return (b / 1048576).toFixed(1) + ' MB';
        if (b >= 1024)       return (b / 1024).toFixed(1) + ' KB';
        return b + ' B';
    }

    function esc(value) {
        return (window.CSS && CSS.escape) ? CSS.escape(value) : value.replace(/["\\]/g, '\\$&');
    }

    // Update the per-group badge + progress bar with how much is selected.
    function recalcGroup(badge) {
        var key       = badge.dataset.group;
        var totFiles  = parseInt(badge.dataset.totalFiles, 10) || 0;
        var totBytes  = parseInt(badge.dataset.totalBytes, 10) || 0;
        var selFiles  = 0, selBytes = 0;

        document.querySelectorAll('.group-' + key + ':checked').forEach(function (cb) {
            selFiles += 1;
            selBytes += parseInt(cb.dataset.bytes, 10) || 0;
        });

        // Sinkronkan master checkbox tri-state dengan pilihan per-file.
        var master = document.querySelector('.master-checkbox[data-group="' + esc(key) + '"]');
        if (master) {
            if (selFiles === 0) {
                master.checked = false; master.indeterminate = false;
            } else if (selFiles === totFiles) {
                master.checked = true;  master.indeterminate = false;
            } else {
                master.checked = false; master.indeterminate = true;
            }
        }

        badge.querySelector('.sel-files').textContent = selFiles.toLocaleString();
        badge.querySelector('.sel-size').textContent  = formatBytes(selBytes);

        // Colour the badge by selection state
        badge.classList.remove('label-default', 'label-warning', 'label-danger');
        if (selFiles === 0) {
            badge.classList.add('label-default');
        } else if (selFiles === totFiles) {
            badge.classList.add('label-danger');
        } else {
            badge.classList.add('label-warning');
        }

        var bar = document.getElementById('selbar-' + key);
        if (bar) {
            var pct = totFiles > 0 ? Math.round((selFiles / totFiles) * 100) : 0;
            bar.style.width = pct + '%';
            bar.classList.remove('progress-bar-warning', 'progress-bar-danger');
            bar.classList.add(selFiles > 0 && selFiles < totFiles ? 'progress-bar-warning' : 'progress-bar-danger');
        }

        return { files: selFiles, bytes: selBytes };
    }

    function recalc() {
        var files = 0, bytes = 0;

        // Per-group badges drive the running totals so each group is counted once,
        // whether it is a bulk group or a per-file group.
        document.querySelectorAll('.group-sel-badge').forEach(function (badge) {
            var g = recalcGroup(badge);
            files += g.files;
            bytes += g.bytes;
        });

        document.getElementById('btn-count').textContent = files.toLocaleString();
        document.getElementById('btn-size').textContent  = formatBytes(bytes);
        document.getElementById('btn-hapus').disabled    = files === 0;
    }

    document.addEventListener('change', function (e) {
        // Master checkbox: teruskan status ke semua file di folder.
        if (e.target.matches('.master-checkbox')) {
            var master  = e.target;
            var checked = master.checked;
            document.querySelectorAll('.group-' + master.dataset.group).forEach(function (cb) {
                cb.checked = checked;
            });
            master.indeterminate = false;
            dirty = true;
            recalc();
        } else if (e.target.matches('.file-checkbox')) {
            dirty = true;
            recalc();
        }
    });

    // ── Buka/tutup daftar file per group ─────────────────────────────────────
    document.addEventListener('click', function (e) {
        if (e.target.matches('.toggle-filelist, .toggle-filelist *')) {
            e.preventDefault();
            var link  = e.target.closest('.toggle-filelist');
            var group = link.dataset.group;
            var list  = document.getElementById('filelist-' + group);
            list.classList.toggle('hidden');
            var icon = link.querySelector('i');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }
    });

    // ── Kirim daftar file yang DIPERTAHANKAN (tidak dicentang) ────────────────
    // Model persisted: server menyimpan daftar keep, lalu menghapus SEMUA hasil scan
    // KECUALI keep. Biasanya sedikit file yang dipertahankan → payload kecil, aman
    // dari batas max_input_vars. Berlaku untuk tombol "Hapus" maupun "Simpan pilihan".
    function currentKeep() {
        var keep = [];
        document.querySelectorAll('.file-checkbox:not(:checked)').forEach(function (cb) {
            keep.push(cb.value);
        });
        return keep;
    }

    var form = document.getElementById('form-bersih');
    if (form) {
        form.addEventListener('submit', function () {
            // Submit (Simpan/Hapus) = menyimpan → jangan tandai dirty lagi.
            dirty = false;

            var jsonField = document.createElement('input');
            jsonField.type  = 'hidden';
            jsonField.name  = 'keep_json';
            jsonField.value = JSON.stringify(currentKeep());
            form.appendChild(jsonField);
        });
    }

    // ── Konfirmasi hapus (tindakan destruktif) ───────────────────────────────
    // Ganti confirm() bawaan browser dengan SweetAlert agar konsisten dengan
    // dialog konfirmasi lain di aplikasi.
    var btnHapus = document.getElementById('btn-hapus');
    if (btnHapus && form) {
        btnHapus.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus file terpilih?',
                html: 'Tindakan ini <strong>tidak dapat dibatalkan</strong>. '
                    + 'Pastikan folder desa sudah dibackup sebelum melanjutkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                confirmButtonColor: '#d33',
                cancelButtonText: 'Batal',
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }
                dirty = false; // hindari peringatan beforeunload
                if (typeof form.requestSubmit === 'function') {
                    // requestSubmit memicu event 'submit' (menyisipkan keep_json) dan
                    // menghormati tombol submitter (aksi form default = hapus).
                    form.requestSubmit(btnHapus);
                } else {
                    // Fallback: form.submit() tidak memicu handler submit, jadi sisipkan
                    // keep_json secara manual.
                    var jsonField = document.createElement('input');
                    jsonField.type  = 'hidden';
                    jsonField.name  = 'keep_json';
                    jsonField.value = JSON.stringify(currentKeep());
                    form.appendChild(jsonField);
                    form.submit();
                }
            });
        });
    }

    // ── Cegah kehilangan pilihan yang belum disimpan ─────────────────────────
    // Simpan pilihan via AJAX (tanpa menghapus) lalu lanjutkan ke tujuan.
    function simpanKeep() {
        var body = new FormData();
        body.append('keep_json', JSON.stringify(currentKeep()));
        if (typeof getCsrfToken === 'function') {
            body.append('sidcsrf', getCsrfToken());
        }
        return fetch('{{ route('database.bersih_folder.simpan') }}', {
            method: 'POST',
            body: body,
            credentials: 'same-origin',
        });
    }

    // Jaring pengaman: peringatan bawaan browser untuk tutup tab / tombol back.
    window.addEventListener('beforeunload', function (e) {
        if (dirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Navigasi dalam halaman (menu samping, tab lain, dsb.): tawarkan simpan.
    document.addEventListener('click', function (e) {
        if (!dirty) {
            return;
        }
        var a = e.target.closest('a[href]');
        if (!a) {
            return;
        }
        var href = a.getAttribute('href') || '';
        if (href === '' || href.charAt(0) === '#' || href.indexOf('javascript:') === 0
            || a.target === '_blank' || a.classList.contains('toggle-filelist')
            || href.indexOf('rescan=1') !== -1) {
            return;
        }

        e.preventDefault();
        var tujuan = a.href;

        Swal.fire({
            title: 'Simpan perubahan?',
            text: 'Ada perubahan pilihan file yang belum disimpan.',
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Simpan lalu lanjut',
            denyButtonText: 'Lanjut tanpa simpan',
            cancelButtonText: 'Batal',
        }).then(function (result) {
            if (result.isConfirmed) {
                dirty = false; // hindari peringatan beforeunload ganda
                simpanKeep()
                    .then(function () { window.location.href = tujuan; })
                    .catch(function () {
                        dirty = true;
                        Swal.fire({
                            title: 'Gagal menyimpan pilihan',
                            text: 'Silakan coba lagi.',
                            icon: 'error',
                        });
                    });
            } else if (result.isDenied) {
                dirty = false; // hindari peringatan beforeunload ganda
                window.location.href = tujuan;
            }
            // Batal / dismiss: tetap di halaman, pilihan masih dianggap belum disimpan.
        });
    }, true);

    recalc();
})();
</script>

@endif

@elseif ($step === 'result')
{{-- ── Halaman hasil ─────────────────────────────────────────────────────── --}}

@if ($result['deleted'] > 0)
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    <strong>Selesai.</strong>
    {{ number_format($result['deleted']) }} file dihapus,
    {{ \App\Services\FolderDesaCleaner\ScanGroup::formatBytes($result['freed_bytes']) }} dibebaskan.
</div>
@else
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i>
    Tidak ada file yang dihapus.
</div>
@endif

@if (! empty($result['errors']))
<div class="alert alert-warning">
    <strong>Peringatan:</strong>
    <ul class="mb-0">
        @foreach ($result['errors'] as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<a href="{{ route('database.bersih_folder') }}" class="btn btn-default btn-social">
    <i class="fa fa-search"></i> Scan ulang
</a>
&nbsp;
<a href="{{ ci_route('database') }}" class="btn btn-default btn-social">
    <i class="fa fa-arrow-left"></i> Kembali ke Database
</a>

@endif

        </div>
    </div>
</div>
