{{-- Keamanan File Tab Content --}}
<div class="row">
    <div class="col-md-12">
        @if ($security)
            {{-- Action Buttons --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Pemindai Keamanan Folder Desa</h3>
                        </div>
                        <div class="box-body">
                            <p>
                                Sistem akan memindai folder <code>desa/</code> untuk mendeteksi file yang tidak dikenal (asing) atau file yang telah dimodifikasi secara tidak sah.
                                <br>
                                - <strong>File Asing</strong>: File yang tidak terdaftar di baseline sistem. Kemungkinan file berbahaya. <strong>Aksi: Hapus</strong>.
                                <br>
                                - <strong>File Dimodifikasi</strong>: File sistem yang isinya telah diubah. <strong>Aksi: Refresh</strong> untuk mengembalikan ke versi asli.
                            </p>
                            <button type="button" class="btn btn-lg btn-primary" onclick="runFullScan()">
                                <i class="fa fa-shield"></i> Mulai Scan Keamanan
                            </button>
                             <div class="mt-2">
                                <small class="text-muted">Proses scan mungkin memerlukan beberapa saat, tergantung jumlah file.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scan Progress --}}
            <div class="row" id="scan-progress" style="display:none;">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-spinner fa-spin"></i> Memindai...</h3>
                        </div>
                        <div class="box-body">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 100%">
                                    Memproses file di folder desa...
                                </div>
                            </div>
                            <p class="text-center" id="scan-status">Mohon tunggu, baseline sedang dibuat (jika belum ada) dan file sedang diperiksa...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scan Results / Quarantine --}}
            <div class="row" id="quarantine-section" style="display:none;">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> File Karantina</h3>
                        </div>
                        <div class="box-body">
                            <div id="scan-summary"></div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tabel-quarantine">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th class="padat">No</th>
                                            <th>Path File</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Rows will be populated by JS --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Security Reports History (Dibiarkan untuk referensi) --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Riwayat Laporan Keamanan (Legacy)</h3>
                             <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body" style="display: none;">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover tabel-daftar" id="tabel-security-reports">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th class="padat">No</th>
                                            <th class="padat">Aksi</th>
                                            <th>Tanggal</th>
                                            <th>Tipe Scan</th>
                                            <th>Total File</th>
                                            <th>Suspicious</th>
                                            <th>Risk Level</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i>
                <strong>Peringatan:</strong> Security Scanner tidak dapat dimuat. Silakan periksa log error untuk detail.
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
/**
 * Security Scanner JavaScript Functions (Refactored)
 */
function renderScanResults(data) {
    const stats = data.statistics;
    const quarantinedFiles = data.quarantined;

    // Update summary
    const summaryHtml = `
        <div class="alert alert-${stats.quarantined_count > 0 ? 'warning' : 'success'}">
            <h4><i class="icon fa fa-${stats.quarantined_count > 0 ? 'warning' : 'check'}"></i> Scan Selesai!</h4>
            Total file dipindai: <strong>${stats.total_scanned}</strong><br>
            File Aman: <strong>${stats.safe_count}</strong><br>
            File dikarantina: <strong>${stats.quarantined_count}</strong>
        </div>
    `;
    $('#scan-summary').html(summaryHtml);

    // Populate table
    const tableBody = $('#tabel-quarantine tbody');
    tableBody.empty();

    if (quarantinedFiles.length > 0) {
         quarantinedFiles.forEach((file, index) => {
            let statusLabel = '';
            let actionButton = '';

            // Escape backslashes in file path for JavaScript onclick attribute
            const escapedPath = file.path.replace(/\\/g, '\\\\');

            if (file.status === 'MODIFIED') {
                statusLabel = '<span class="label label-warning">FILE DIMODIFIKASI</span>';
                // actionButton = `<button type="button" class="btn btn-sm btn-info" onclick="restoreFile('${escapedPath}', this)"><i class="fa fa-refresh"></i> Refresh</button>`;
            } else if (file.status === 'FOREIGN') {
                statusLabel = '<span class="label label-danger">FILE ASING</span>';
                actionButton = `<button type="button" class="btn btn-sm btn-danger" onclick="deleteFile('${escapedPath}', this)"><i class="fa fa-trash"></i> Hapus</button>`;
            }

            const row = `
                <tr id="row-${index}">
                    <td>${index + 1}</td>
                    <td><code>${file.path}</code></td>
                    <td class="text-center">${statusLabel}</td>
                    <td class="text-center">${actionButton}</td>
                </tr>
            `;
            tableBody.append(row);
        });
        $('#quarantine-section').show();
    } else {
         tableBody.append('<tr><td colspan="4" class="text-center">Tidak ada file yang dikarantina. Semua file aman.</td></tr>');
         $('#quarantine-section').show();
    }
}

function runFullScan() {
    $('#scan-progress').show();
    $('#quarantine-section').hide();
    $('#scan-status').text('Memulai scan keamanan...');

    $.ajax({
        url: '{{ ci_route("info_sistem.security_default_scan") }}',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            $('#scan-progress').hide();

            if (response.success) {
                const data = response.data;
                const stats = data.statistics;

                // Render and display the results
                renderScanResults(data);

                // Store results in session storage to persist after refresh
                sessionStorage.setItem('latestScanResult', JSON.stringify(data));

                // Reload the legacy reports datatable
                if (tableSecurityReportsInitialized) {
                    TableSecurityReports.draw();
                }

                Swal.fire({
                    icon: stats.quarantined_count > 0 ? 'warning' : 'success',
                    title: 'Scan Selesai!',
                    text: `Ditemukan ${stats.quarantined_count} file yang perlu diperiksa.`,
                    confirmButtonText: 'OK'
                });

            } else {
                // Clear previous results on failure
                sessionStorage.removeItem('latestScanResult');

                // Handle specific error for missing baseline
                if (response.message && response.message.includes('Baseline tidak ditemukan')) {
                    Swal.fire({
                        title: 'Baseline Belum Dibuat',
                        text: "Ini sepertinya pemindaian pertama Anda. Untuk melanjutkan, sistem perlu membuat 'baseline' (acuan file aman). Apakah Anda ingin membuatnya sekarang?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Buat Baseline',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            generateBaseline();
                        }
                    });
                } else {
                    // Handle other generic errors
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan yang tidak diketahui.',
                        confirmButtonText: 'OK'
                    });
                }
            }
        },
        error: function(xhr) {
            $('#scan-progress').hide();
            // Clear previous results on error
            sessionStorage.removeItem('latestScanResult');
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menjalankan scan. Silakan coba lagi.',
                confirmButtonText: 'OK'
            });
        }
    });
}

function generateBaseline() {
    $('#scan-progress').show();
    $('#quarantine-section').hide();
    $('#scan-status').text('Membuat baseline keamanan, ini mungkin butuh beberapa saat...');

    $.ajax({
        url: '{{ ci_route("info_sistem.security_default_generate_baseline") }}', // PERHATIAN: Route ini perlu dibuat di backend
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            $('#scan-progress').hide();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Baseline Berhasil Dibuat!',
                    text: 'Silakan klik tombol "Mulai Scan Keamanan" lagi untuk menjalankan pemindaian.',
                    confirmButtonText: 'Mengerti'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Membuat Baseline!',
                    text: response.message || 'Terjadi kesalahan yang tidak diketahui.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr) {
            $('#scan-progress').hide();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menghubungi server untuk membuat baseline.',
                confirmButtonText: 'OK'
            });
        }
    });
}

function deleteFile(filePath, btn) {
    Swal.fire({
        title: 'Hapus File Asing?',
        html: `Anda yakin ingin menghapus file ini secara permanen?<br><code>${filePath}</code>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $(btn).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');
            $.ajax({
                url: '{{ ci_route("info_sistem.security_default_delete_file") }}',
                method: 'POST',
                dataType: 'json',
                data: { file_path: filePath },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        $(btn).closest('tr').remove();
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                        $(btn).prop('disabled', false).html('<i class="fa fa-trash"></i> Hapus');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                    $(btn).prop('disabled', false).html('<i class="fa fa-trash"></i> Hapus');
                }
            });
        }
    });
}

function restoreFile(filePath, btn) {
    Swal.fire({
        title: 'Refresh File?',
        html: `Anda yakin ingin mengembalikan file ini ke versi asli?<br><code>${filePath}</code><br><br>Perubahan lokal pada file ini akan hilang.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Refresh!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $(btn).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memulihkan...');
             $.ajax({
                url: '{{ ci_route("info_sistem.security_default_restore_file") }}',
                method: 'POST',
                dataType: 'json',
                data: { file_path: filePath },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success');
                         $(btn).closest('tr').find('td:eq(2)').html('<span class="label label-success">DIPULIHKAN</span>');
                        $(btn).closest('td').html('-');
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                        $(btn).prop('disabled', false).html('<i class="fa fa-refresh"></i> Refresh');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                    $(btn).prop('disabled', false).html('<i class="fa fa-refresh"></i> Refresh');
                }
            });
        }
    });
}


// Initialize Security Reports DataTable (legacy)
let TableSecurityReports;
let tableSecurityReportsInitialized = false;

function loadSecurityReports() {
    if ($('#tabel-security-reports').is(':visible') && !tableSecurityReportsInitialized) {

        TableSecurityReports = $('#tabel-security-reports').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            paging: true,
            searching: false,
            ordering: false,
            ajax: {
                url: "{{ ci_route('info_sistem.security_default_reports') }}",
                method: 'POST',
                data: function (d) {
                    d.type = 'scan';
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false },
                { data: 'aksi', orderable: false },
                { data: 'date', orderable: false },
                { data: 'scan_type', orderable: false },
                { data: 'total_files', orderable: false },
                { data: 'suspicious_count', orderable: false },
                { data: 'max_risk', orderable: false }
            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>',
                emptyTable: 'Belum ada laporan keamanan.',
                zeroRecords: 'Tidak ada laporan yang cocok'
            }
        });

        tableSecurityReportsInitialized = true;

    } else if (tableSecurityReportsInitialized) {
        TableSecurityReports.columns.adjust().responsive.recalc();
    }
}

$(document).on('expanded.boxwidget', '.box', function () {
    loadSecurityReports();
});

/**
 * View detailed report
 */
function viewReport(filename) {
    Swal.fire({
        title: 'Memuat Laporan...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    let url = '{{ ci_route("info_sistem.security_default_view_report", ["filename" => "PLACEHOLDER"]) }}';
    url = url.replace('PLACEHOLDER', encodeURIComponent(filename).replace(/\./g, '~'));

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const scanType = data.scan_type || 'integrity';

                // Extract suspicious files from new_files if present
                let filesFromNew = [];
                if (data.new_files && Array.isArray(data.new_files)) {
                    filesFromNew = data.new_files.filter(file => (scanType === 'scan' ? true : file.suspicious));
                }

                // Get suspicious_files or files
                let otherFiles = data.suspicious_files || data.files || [];

                // If suspiciousFiles is object (full scan format), convert to array
                if (!Array.isArray(otherFiles)) {
                    otherFiles = Object.entries(otherFiles)
                        .filter(([path, file]) => (scanType === 'scan' ? true : file.suspicious))
                        .map(([path, file]) => ({ path, ...file }));
                } else if (scanType !== 'scan') {
                    // For other types, ensure we only have suspicious files if it's an array
                    otherFiles = otherFiles.filter(file => file.suspicious);
                }

                // Combine suspicious from new_files and existing suspicious_files
                const filesToList = [...filesFromNew, ...otherFiles];

                // Remove duplicates based on path
                const uniqueFilesToList = filesToList.filter((file, index, self) =>
                    self.findIndex(f => f.path === file.path) === index
                );

                // Handle different JSON formats for totals
                const totalFiles = data.total_scanned || (data.statistics ? (data.statistics.total_checked || data.statistics.total_scanned) : 0);
                const suspiciousCount = uniqueFilesToList.filter(file => file.suspicious).length;
                const newCount = data.new_files ? data.new_files.length : 0;
                const modifiedCount = data.modified_files ? data.modified_files.length : 0;
                const deletedCount = data.deleted_files ? data.deleted_files.length : 0;
                const quarantinedFiles = data.quarantined || [];
                const quarantinedCount = quarantinedFiles.length;

                // Format scan type display
                let scanTypeDisplay = '';
                if (scanType === 'full') {
                    scanTypeDisplay = 'Full Scan';
                } else if (scanType === 'scan') {
                    scanTypeDisplay = 'Scan';
                } else if (scanType === 'integrity') {
                    scanTypeDisplay = 'Integrity Check';
                } else {
                    scanTypeDisplay = scanType.charAt(0).toUpperCase() + scanType.slice(1);
                }

                // Get scan date
                const scanDate = data.scan_date || data.checked_at || 'N/A';

                let html = `
                    <div style="text-align: left;">
                        <hr>
                        <p><strong>Tanggal Scan:</strong> ${scanDate}</p>
                        <p><strong>Tipe Scan:</strong> ${scanTypeDisplay}</p>
                        <p><strong>Total File:</strong> ${totalFiles}</p>
                        <p><strong>File Karantina:</strong> <span class="label label-${quarantinedCount > 0 ? 'danger' : 'success'}">${quarantinedCount}</span></p>
                        <hr>
                `;

                // Display new files
                if (newCount > 0) {
                    html += '<h5><strong>File Baru:</strong></h5>';
                    html += '<div style="max-height: 200px; overflow-y: auto;">';
                    html += '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>File</th><th>Ukuran</th><th>Modified</th><th>Status</th></tr></thead>';
                    html += '<tbody>';

                    data.new_files.forEach(function(file) {
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');
                        const isSuspicious = file.suspicious ? '<span class="label label-danger">Suspicious</span>' : '<span class="label label-success">Clean</span>';

                        html += `
                            <tr>
                                <td><small><code>${relativePath}</code></small></td>
                                <td><small>${file.size} bytes</small></td>
                                <td><small>${file.modified}</small></td>
                                <td>${isSuspicious}</td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';
                    html += '</div><br>';
                }

                // Display modified files
                if (modifiedCount > 0) {
                    html += '<h5><strong>File Dimodifikasi:</strong></h5>';
                    html += '<div style="max-height: 200px; overflow-y: auto;">';
                    html += '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>File</th><th>Ukuran</th><th>Modified</th></tr></thead>';
                    html += '<tbody>';

                    data.modified_files.forEach(function(file) {
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');

                        html += `
                            <tr>
                                <td><small><code>${relativePath}</code></small></td>
                                <td><small>${file.size} bytes</small></td>
                                <td><small>${file.modified}</small></td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';
                    html += '</div><br>';
                }

                // Display deleted files
                if (deletedCount > 0) {
                    html += '<h5><strong>File Dihapus:</strong></h5>';
                    html += '<div style="max-height: 200px; overflow-y: auto;">';
                    html += '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>File</th></tr></thead>';
                    html += '<tbody>';

                    data.deleted_files.forEach(function(file) {
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');

                        html += `
                            <tr>
                                <td><small><code>${relativePath}</code></small></td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';
                    html += '</div><br>';
                }

                // Display quarantined files
                if (quarantinedCount > 0) {
                    html += '<h5><strong>File Karantina:</strong></h5>';
                    html += '<div style="max-height: 400px; overflow-y: auto;">';
                    html += '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>File</th><th>Status</th><th>Ukuran</th></tr></thead>';
                    html += '<tbody>';

                    quarantinedFiles.forEach(function(file) {
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');
                        const statusLabel = file.status === 'MODIFIED' ? '<span class="label label-warning">MODIFIED</span>' : '<span class="label label-danger">FOREIGN</span>';

                        html += `
                            <tr>
                                <td><small><code>${relativePath}</code></small></td>
                                <td>${statusLabel}</td>
                                <td><small>${file.size} bytes</small></td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';
                    html += '</div><br>';
                }

                if (uniqueFilesToList.length > 0) {
                    const tableTitle = (scanType === 'scan') ? 'Semua File yang Ditemukan' : 'File Mencurigakan';
                    html += `<h5><strong>${tableTitle}:</strong></h5>`;
                    html += '<div style="max-height: 400px; overflow-y: auto;">';
                    html += '<table class="table table-sm table-bordered">';
                    html += (scanType === 'scan')
                        ? '<thead><tr><th>File</th><th>Status</th><th>Risk</th><th>Score</th><th>Categories</th></tr></thead>'
                        : '<thead><tr><th>File</th><th>Risk</th><th>Score</th><th>Categories</th></tr></thead>';
                    html += '<tbody>';

                    // Display all unique suspicious files
                    uniqueFilesToList.forEach(function(file) {
                        const labelClass = RISK_LEVELS[file.risk_level] || 'default';
                        const isSuspicious = file.suspicious ? '<span class="label label-danger">Suspicious</span>' : '<span class="label label-success">Clean</span>';

                        // Extract relative path from full path
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');

                        html += `<tr><td><small><code>${relativePath}</code></small></td>`;
                        if (scanType === 'scan') {
                            html += `<td>${isSuspicious}</td>`;
                        }
                        html += `<td><span class="label label-${labelClass}">${file.risk_level || 'N/A'}</span></td>`;
                        html += `<td><strong>${file.risk_score || 'N/A'}</strong></td>`;
                        html += `<td><small>${(file.categories || []).join(', ')}</small></td>`;
                        html += '</tr>';
                    });

                    html += '</tbody></table>';
                    html += '</div>';
                } else if (scanType !== 'scan' && newCount === 0 && modifiedCount === 0 && deletedCount === 0 && quarantinedCount === 0) {
                    html += '<div class="alert alert-success"><i class="fa fa-check"></i> Semua file aman, tidak ada perubahan atau file mencurigakan.</div>';
                }

                html += '</div>';

                Swal.fire({
                    title: 'Detail Laporan',
                    html: html,
                    width: '80%',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'swal-wide'
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat laporan.',
                confirmButtonText: 'OK'
            });
        }
    });
}

$(document).ready(function() {
    const latestScanResult = sessionStorage.getItem('latestScanResult');
    if (latestScanResult) {
        try {
            renderScanResults(JSON.parse(latestScanResult));
        } catch (e) {
            console.error("Gagal mem-parsing hasil scan dari session storage:", e);
            sessionStorage.removeItem('latestScanResult');
        }
    }
});

/**
 * Delete security report
 */
function deleteReport(filename) {
    Swal.fire({
        title: 'Hapus Laporan?',
        text: 'Laporan ini akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: '{{ ci_route("info_sistem.security_default_delete_report", ["filename" => ""]) }}'.replace(/\/$/, '') + '/' + encodeURIComponent(filename).replace(/\./g, '~'),
                method: 'POST',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }
                return response;
            }).catch(error => {
                Swal.showValidationMessage(`Request failed: ${error.message || error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.value.message || 'Laporan berhasil dihapus.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload DataTable
                if (TableSecurityReports && typeof TableSecurityReports.ajax !== 'undefined') {
                    TableSecurityReports.ajax.reload(null, false); // false = stay on current page
                } else {
                    // Fallback: reload page if DataTable not initialized
                    window.location.reload();
                }
            });
        }
    });
}

</script>
@endpush
