{{-- Keamanan File Tab Content --}}
<div class="row">
    <div class="col-md-12">
        @if ($security)
            {{-- Overview Cards --}}
            <div class="row">
                {{-- Baseline Status --}}
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="small-box {{ $security['baseline'] ? 'bg-green' : 'bg-yellow' }}">
                        <div class="inner">
                            <h3>{{ $security['baseline'] ? 'Aktif' : 'Belum' }}</h3>
                            <p>Status Baseline</p>
                        </div>
                    </div>
                </div>

                {{-- Total Files (dari baseline) --}}
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>{{ $security['baseline']['total_files'] ?? 0 }}</h3>
                            <p>Total File di Baseline</p>
                        </div>
                    </div>
                </div>

                {{-- Detection Patterns --}}
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $security['pattern_stats']['total_patterns'] }}</h3>
                            <p>Detection Patterns</p>
                        </div>
                    </div>
                </div>

                {{-- Pattern Categories --}}
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>{{ $security['pattern_stats']['total_categories'] }}</h3>
                            <p>Pattern Categories</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Aksi Keamanan</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-camera" style="display: inline !important;"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Generate Baseline</span>
                                            <span class="info-box-number" style="font-size: 15px;">Snapshot kondisi file saat ini</span>
                                            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="generateBaseline()">
                                                <i class="fa fa-refresh"></i> {{ $security['baseline'] ? 'Regenerate' : 'Generate' }} Baseline
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-search" style="display: inline !important;"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Integrity Check</span>
                                            <span class="info-box-number" style="font-size: 15px;">Bandingkan dengan baseline</span>
                                            <button type="button" class="btn btn-sm btn-warning btn-block" onclick="checkIntegrity()" {{ !$security['baseline'] ? 'disabled' : '' }}>
                                                <i class="fa fa-check-circle"></i> Check Integrity
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-red"><i class="fa fa-bug" style="display: inline !important;"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Full Scan</span>
                                            <span class="info-box-number" style="font-size: 15px;">Deteksi file mencurigakan</span>
                                            <button type="button" class="btn btn-sm btn-danger btn-block" onclick="fullScan()">
                                                <i class="fa fa-shield"></i> Scan Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baseline Info --}}
            @if ($security['baseline'])
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Baseline</h3>
                                <div class="box-tools">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteBaseline()">
                                        <i class="fa fa-trash"></i> Hapus Baseline
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="40%">Tanggal Dibuat</th>
                                        <td>{{ $security['baseline']['generated_at'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total File</th>
                                        <td>{{ number_format($security['baseline']['total_files']) }} files</td>
                                    </tr>
                                    @if (isset($security['baseline']['total_size']))
                                        <tr>
                                            <th>Total Ukuran</th>
                                            <td>{{ number_format($security['baseline']['total_size'] ?? 0, 0, ',', '.') }} bytes ({{ ceil(($security['baseline']['total_size'] ?? 0) / (1024 * 1024)) }} MB)</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>PHP Files</th>
                                        <td>{{ number_format($security['baseline']['statistics']['php_files'] ?? 0) }} files</td>
                                    </tr>
                                    <tr>
                                        <th>Suspicious Files</th>
                                        <td><span class="label label-{{ ($security['baseline']['statistics']['suspicious_files'] ?? 0) > 0 ? 'danger' : 'success' }}">
                                            {{ $security['baseline']['statistics']['suspicious_files'] ?? 0 }} files
                                        </span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-shield"></i> Detection Pattern Statistics</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th class="text-center">Patterns</th>
                                            <th class="text-center">Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $categories = $security['pattern_stats']['categories'];
                                            // Sort by weight descending
                                            uasort($categories, function($a, $b) {
                                                return $b['weight'] - $a['weight'];
                                            });
                                        @endphp
                                        @foreach (array_slice($categories, 0, 10) as $category => $stats)
                                            <tr>
                                                <td><span class="label label-{{ $stats['weight'] >= 40 ? 'danger' : ($stats['weight'] >= 30 ? 'warning' : 'info') }}">{{ ucfirst($category) }}</span></td>
                                                <td class="text-center">{{ $stats['count'] }}</td>
                                                <td class="text-center"><strong>{{ $stats['weight'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($categories) > 10)
                                    <p class="text-muted text-center">... and {{ count($categories) - 10 }} more categories</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Scan Progress --}}
            <div class="row" id="scan-progress" style="display:none;">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-spinner fa-spin"></i> Scanning...</h3>
                        </div>
                        <div class="box-body">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 100%">
                                    Memproses...
                                </div>
                            </div>
                            <p class="text-center" id="scan-status">Mohon tunggu, proses scanning sedang berjalan...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scan Results --}}
            <div class="row" id="scan-results" style="display:none;">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> Hasil Scan</h3>
                            <div class="box-tools">
                                <button type="button" class="btn btn-sm btn-default" onclick="$('#scan-results').hide()">
                                    <i class="fa fa-times"></i> Tutup
                                </button>
                            </div>
                        </div>
                        <div class="box-body" id="scan-results-content">
                            {{-- Results will be loaded here via AJAX --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Security Reports History --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Riwayat Laporan Keamanan</h3>
                        </div>
                        <div class="box-body">
                            <div class="row mepet">
                                <div class="col-sm-2">
                                    <select id="type" class="form-control input-sm select2" name="type">
                                        <option value="" >Pilih Tipe Scan</option>
                                        <option value="integrity">Integrity</option>
                                        <option value="scan">Scan</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="batas">
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
 * Security Scanner JavaScript Functions
 * Handles baseline generation, integrity checks, full scans, and report management
 */

// Constants for risk levels
const RISK_LEVELS = {
    CRITICAL: 'danger',
    HIGH: 'warning',
    MEDIUM: 'info',
    LOW: 'default'
};

/**
 * Generate baseline for file integrity monitoring
 */
function generateBaseline() {
    Swal.fire({
        title: 'Generate Baseline?',
        html: 'Proses ini akan membuat snapshot dari kondisi file di folder <strong>desa/</strong> saat ini.<br><br>' +
              'Proses mungkin memakan waktu beberapa menit tergantung jumlah file.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Generate!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return $.ajax({
                url: '{{ ci_route("info_sistem.security_generate_baseline") }}',
                type: 'POST',
                dataType: 'json'
            }).then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }
                return response;
            }).catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: `<strong>Baseline berhasil dibuat/diperbarui!</strong><br><br>` +
                      `Total Files: ${result.value.data.statistics.total_files}<br>` +
                      `PHP Files: ${result.value.data.statistics.php_files}<br>` +
                      `Suspicious: ${result.value.data.statistics.suspicious_files}`,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.hash = '#keamanan';
                window.location.reload();
            });
        }
    });
}

/**
 * Check integrity against baseline
 */
function checkIntegrity() {
    $('#scan-progress').show();
    $('#scan-results').hide();
    $('#scan-status').text('Checking integrity...');

    $.ajax({
        url: '{{ ci_route("info_sistem.security_check_integrity") }}',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            $('#scan-progress').hide();

            if (response.success) {
                const data = response.data;
                const stats = data.statistics;

                let resultHtml = `
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <strong>Integrity Check Completed</strong><br>
                                Checked at: ${data.checked_at}<br>
                                Baseline: ${data.baseline_date}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-files-o" style="display: inline !important;"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Checked</span>
                                    <span class="info-box-number">${stats.total_checked}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-plus" style="display: inline !important;"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">New Files</span>
                                    <span class="info-box-number">${stats.new_count}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-edit" style="display: inline !important;"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Modified</span>
                                    <span class="info-box-number">${stats.modified_count}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-red">
                                <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Suspicious</span>
                                    <span class="info-box-number">${stats.suspicious_count}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Show suspicious files
                if (stats.suspicious_count > 0) {
                    resultHtml += `
                        <div class="alert alert-danger">
                            <h4><i class="fa fa-ban"></i> Suspicious Files Detected!</h4>
                            File-file berikut terdeteksi mencurigakan:
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>File Path</th>
                                    <th>Risk Level</th>
                                    <th>Score</th>
                                    <th>Categories</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    data.suspicious_files.forEach(function(file) {
                        const labelClass = RISK_LEVELS[file.risk_level] || 'default';

                        // Extract relative path from full path
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');

                        resultHtml += `
                            <tr>
                                <td><code>${relativePath}</code></td>
                                <td><span class="label label-${labelClass}">${file.risk_level}</span></td>
                                <td><strong>${file.risk_score}</strong></td>
                                <td>${file.categories.join(', ')}</td>
                                <td><small>${file.recommendation}</small></td>
                            </tr>
                        `;
                    });

                    resultHtml += `
                            </tbody>
                        </table>
                    `;
                }

                $('#scan-results-content').html(resultHtml);
                $('#scan-results').show();

                // Show notification
                if (stats.suspicious_count > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan Keamanan!',
                        text: `Ditemukan ${stats.suspicious_count} file mencurigakan yang perlu ditindaklanjuti.`,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Integrity Check Berhasil!',
                        text: 'Tidak ada file mencurigakan ditemukan. Semua file sesuai dengan baseline.',
                        confirmButtonText: 'OK'
                    });
                }

                // Reload DataTable untuk menampilkan report terbaru
                if (TableSecurityReports && typeof TableSecurityReports.ajax !== 'undefined') {
                    TableSecurityReports.ajax.reload(null, false);
                }
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
            $('#scan-progress').hide();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat checking integrity. Silakan coba lagi.',
                confirmButtonText: 'OK'
            });
        }
    });
}

/**
 * Perform full scan for suspicious files
 */
function fullScan() {
    $('#scan-progress').show();
    $('#scan-results').hide();
    $('#scan-status').text('Scanning for suspicious files...');

    $.ajax({
        url: '{{ ci_route("info_sistem.security_full_scan") }}',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            $('#scan-progress').hide();

            if (response.success) {
                const data = response.data;

                let resultHtml = `
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Scanned</span>
                                    <span class="info-box-number">${data.total_scanned}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Clean Files</span>
                                    <span class="info-box-number">${data.clean_count}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-red">
                                <span class="info-box-icon"><i class="fa fa-bug"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Suspicious</span>
                                    <span class="info-box-number">${data.suspicious_count}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gray">
                                <span class="info-box-icon"><i class="fa fa-ban"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Skipped</span>
                                    <span class="info-box-number">${data.skipped_count}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Show suspicious files
                if (data.suspicious_count > 0) {
                    resultHtml += `
                        <div class="alert alert-danger">
                            <h4><i class="fa fa-ban"></i> Suspicious Files Detected!</h4>
                            Ditemukan ${data.suspicious_count} file mencurigakan yang perlu ditindaklanjuti.
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>File Path</th>
                                    <th>Risk Level</th>
                                    <th>Score</th>
                                    <th>Categories</th>
                                    <th>Recommendation</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    Object.entries(data.files).forEach(function([filepath, file]) {
                        const labelClass = RISK_LEVELS[file.risk_level] || 'default';

                        // Extract relative path from full path
                        const relativePath = filepath.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');

                        resultHtml += `
                            <tr>
                                <td><code>${relativePath}</code></td>
                                <td><span class="label label-${labelClass}">${file.risk_level}</span></td>
                                <td><strong>${file.risk_score}</strong></td>
                                <td><small>${file.categories.join(', ')}</small></td>
                                <td><small>${file.recommendation}</small></td>
                            </tr>
                        `;
                    });

                    resultHtml += `
                            </tbody>
                        </table>
                    `;
                } else {
                    resultHtml += `
                        <div class="alert alert-success">
                            <h4><i class="fa fa-check"></i> All Clear!</h4>
                            Tidak ada file mencurigakan ditemukan. Folder desa/ aman.
                        </div>
                    `;
                }

                $('#scan-results-content').html(resultHtml);
                $('#scan-results').show();

                // Reload DataTable untuk menampilkan report terbaru
                if (TableSecurityReports && typeof TableSecurityReports.ajax !== 'undefined') {
                    TableSecurityReports.ajax.reload(null, false);
                }

                // Show notification
                if (data.suspicious_count > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan Keamanan!',
                        html: `<strong>Ditemukan ${data.suspicious_count} file mencurigakan!</strong><br><br>` +
                              `Total Scanned: ${data.total_scanned}<br>` +
                              `Clean: ${data.clean_count}<br>` +
                              `Suspicious: ${data.suspicious_count}`,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Scan Selesai!',
                        html: `<strong>Tidak ada file mencurigakan ditemukan!</strong><br><br>` +
                              `Total Scanned: ${data.total_scanned}<br>` +
                              `Semua file aman.`,
                        confirmButtonText: 'OK'
                    });
                }
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
            $('#scan-progress').hide();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat scanning. Silakan coba lagi.',
                confirmButtonText: 'OK'
            });
        }
    });
}

/**
 * Delete baseline
 */
function deleteBaseline() {
    Swal.fire({
        title: 'Hapus Baseline?',
        text: 'Baseline akan dihapus dan Anda perlu generate ulang untuk integrity check.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ ci_route("info_sistem.security_delete_baseline") }}',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Dihapus!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.hash = '#keamanan';
                            window.location.reload();
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
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus baseline. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

// Initialize Security Reports DataTable
let TableSecurityReports;
let tableSecurityReportsInitialized = false;

/**
 * Load and initialize security reports DataTable
 */
function loadSecurityReports() {
    if (tableSecurityReportsInitialized) {
        // Jika sudah di-initialize, reload saja
        if (TableSecurityReports && typeof TableSecurityReports.ajax !== 'undefined') {
            TableSecurityReports.ajax.reload(null, false);
        }
        return;
    }

    TableSecurityReports = $('#tabel-security-reports').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: false,
        ordering: false,
        ajax: {
            url: "{{ ci_route('info_sistem.security_reports') }}",
            type: 'GET',
            data: function(d) {
                d.type = $('#type').val();
            },
            error: function(xhr, error, code) {
                console.error('DataTable Ajax Error:', error, code);
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                class: 'padat',
                searchable: false,
                orderable: false
            },
            {
                data: 'aksi',
                name: 'aksi',
                class: 'padat',
                searchable: false,
                orderable: false
            },
            {
                data: 'date',
                name: 'date',
                searchable: false,
                orderable: false
            },
            {
                data: 'scan_type',
                name: 'scan_type',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                data: 'total_files',
                name: 'total_files',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                data: 'suspicious_count',
                name: 'suspicious_count',
                class: 'text-center',
                searchable: false,
                orderable: false
            },
            {
                data: 'max_risk',
                name: 'max_risk',
                class: 'text-center',
                orderable: false,
            }
        ],
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>',
            emptyTable: 'Belum ada laporan keamanan. Silakan jalankan scan terlebih dahulu.',
            zeroRecords: 'Tidak ada laporan yang cocok dengan pencarian'
        }
    });

    tableSecurityReportsInitialized = true;

    $('#type').on('select2:select', function(e) {
        TableSecurityReports.draw();
    });
}

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

    $.ajax({
        url: '{{ ci_route("info_sistem.security_view_report", ["filename" => ""]) }}'.replace(/\/$/, '') + '/' + filename,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;

                // Extract suspicious files from new_files if present
                let suspiciousFromNew = [];
                if (data.new_files && Array.isArray(data.new_files)) {
                    suspiciousFromNew = data.new_files.filter(file => file.suspicious);
                }

                // Get suspicious_files or files
                let suspiciousFiles = data.suspicious_files || data.files || [];

                // If suspiciousFiles is object (full scan format), convert to array
                if (!Array.isArray(suspiciousFiles)) {
                    suspiciousFiles = Object.entries(suspiciousFiles)
                        .filter(([path, file]) => file.suspicious)
                        .map(([path, file]) => ({ path, ...file }));
                }

                // Combine suspicious from new_files and existing suspicious_files
                const allSuspicious = [...suspiciousFromNew, ...suspiciousFiles];

                // Remove duplicates based on path
                const uniqueSuspicious = allSuspicious.filter((file, index, self) =>
                    self.findIndex(f => f.path === file.path) === index
                );

                // Handle different JSON formats for totals
                const totalFiles = data.total_scanned || (data.statistics ? data.statistics.total_checked : 0);
                const suspiciousCount = uniqueSuspicious.length;
                const newCount = data.new_files ? data.new_files.length : 0;
                const modifiedCount = data.modified_files ? data.modified_files.length : 0;
                const deletedCount = data.deleted_files ? data.deleted_files.length : 0;

                // Format scan type display
                let scanTypeDisplay = '';
                const scanType = data.scan_type || 'integrity';
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
                        <p><strong>File Baru:</strong> <span class="label label-info">${newCount}</span></p>
                        <p><strong>File Dimodifikasi:</strong> <span class="label label-warning">${modifiedCount}</span></p>
                        <p><strong>File Dihapus:</strong> <span class="label label-default">${deletedCount}</span></p>
                        <p><strong>File Suspicious:</strong> <span class="label label-${suspiciousCount > 0 ? 'danger' : 'success'}">${suspiciousCount}</span></p>
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

                if (suspiciousCount > 0) {
                    html += '<h5><strong>File Mencurigakan:</strong></h5>';
                    html += '<div style="max-height: 400px; overflow-y: auto;">';
                    html += '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>File</th><th>Risk</th><th>Score</th><th>Categories</th></tr></thead>';
                    html += '<tbody>';

                    // Display all unique suspicious files
                    uniqueSuspicious.forEach(function(file) {
                        const labelClass = RISK_LEVELS[file.risk_level] || 'default';

                        // Extract relative path from full path
                        const relativePath = file.path.replace(/^.*[\/\\]desa[\/\\]/, 'desa/');

                        html += `
                            <tr>
                                <td><small><code>${relativePath}</code></small></td>
                                <td><span class="label label-${labelClass}">${file.risk_level}</span></td>
                                <td><strong>${file.risk_score}</strong></td>
                                <td><small>${file.categories.join(', ')}</small></td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';
                    html += '</div>';
                } else if (newCount === 0 && modifiedCount === 0 && deletedCount === 0) {
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
                url: '{{ ci_route("info_sistem.security_delete_report", ["filename" => ""]) }}'.replace(/\/$/, '') + '/' + filename,
                type: 'POST',
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
