@if (in_array('keuangan_template_tidak_sama', $masalah))
<div class="panel panel-default" id="panel-keuangan-template">
    <div class="panel-body">
        <strong>Terdeteksi format / kode Template Keuangan tidak sesuai dengan KeuanganTemplateSeeder</strong>

        <div class="table-responsive" style="margin-top: 10px;">
            <table class="table table-bordered table-hover" id="table-keuangan-template">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 40px;">No</th>
                        <th>Uraian</th>
                        <th>UUID Saat Ini</th>
                        <th>Parent UUID Saat Ini</th>
                        <th>Ketidaksesuaian / Masalah</th>
                        <th>Rekomendasi (Seharusnya)</th>
                        <th class="text-center">Rencana Tindakan</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($keuangan_template_tidak_sama as $idx => $item)
                    <tr id="kt-row-{{ $idx }}" 
                        data-current-uuid="{{ $item['current_uuid'] }}"
                        data-expected-uuid="{{ $item['expected_uuid'] }}"
                        data-expected-parent-uuid="{{ $item['expected_parent_uuid'] ?? '' }}"
                        data-uraian="{{ $item['uraian'] }}">
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $item['uraian'] }}</td>
                        <td><code>{{ $item['current_uuid'] }}</code></td>
                        <td>
                            @if ($item['current_parent_uuid'] === null)
                                <span class="label label-default">null</span>
                            @else
                                <code>{{ $item['current_parent_uuid'] }}</code>
                            @endif
                        </td>
                        <td><span class="text-red">{{ $item['problems'] }}</span></td>
                        <td>
                            @if ($item['expected_uuid'] === null)
                                <span class="label label-default">Perlu konfirmasi manual</span>
                            @else
                                UUID: <code>{{ $item['expected_uuid'] }}</code>,
                                Parent: @if ($item['expected_parent_uuid'] === null) <span class="label label-default">null</span> @else <code>{{ $item['expected_parent_uuid'] }}</code> @endif
                            @endif
                        </td>
                        <td class="text-center">
                            @if (($item['tindakan'] ?? '') === 'Hapus & Pindahkan Relasi')
                                <span class="label label-danger"><i class="fa fa-trash"></i> Hapus & Pindahkan Relasi</span>
                            @elseif (($item['tindakan'] ?? '') === 'Tambah Data')
                                <span class="label label-primary"><i class="fa fa-plus"></i> Tambah Data</span>
                            @elseif (($item['tindakan'] ?? '') === 'Perlu Konfirmasi (Ambigu)')
                                <span class="label label-warning"><i class="fa fa-question-circle"></i> Perlu Konfirmasi (Ambigu)</span>
                            @else
                                <span class="label label-info"><i class="fa fa-edit"></i> Perbarui Parent UUID</span>
                            @endif
                        </td>
                        <td class="text-center kt-status-cell">
                            <span class="label label-warning kt-badge-pending">Belum Diperbaiki</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p>
            Klik tombol Perbaiki untuk menyesuaikan kode UUID dan parent_uuid pada tabel <code>keuangan_template</code>.
            <br>
            <button type="button" id="btn-perbaiki-kt-ajax" class="btn btn-sm btn-social btn-danger" style="margin-top: 5px;">
                <i class="fa fa-wrench"></i>Perbaiki Data
            </button>
        </p>
    </div>
</div>

<script>
(function initKeuanganTemplateAjax() {
    if (typeof jQuery === 'undefined' || typeof Swal === 'undefined' || typeof jQuery.fn.DataTable === 'undefined') {
        setTimeout(initKeuanganTemplateAjax, 50);
        return;
    }

    if (!jQuery.fn.DataTable.isDataTable('#table-keuangan-template')) {
        jQuery('#table-keuangan-template').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            language: {
                url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
            },
            columnDefs: [
                { orderable: false, targets: [0, 6, 7] }
            ]
        });
    }

    jQuery(document).on('click', '#btn-perbaiki-kt-ajax', function (e) {
        e.preventDefault();

        const url  = '{{ route('periksa.perbaiki.keuangan_template_item') }}';
        const csrf = { name: '{{ $token_name }}', value: '{{ $token_value }}' };

        Swal.fire({
            title: 'Konfirmasi Perbaikan',
            html: '<p>Apakah Anda yakin ingin memperbaiki data template keuangan?</p>' +
                  '<p class="text-warning"><strong>Pastikan sudah melakukan backup database/folder desa sebelum melanjutkan.</strong></p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-check"></i> Ya, Perbaiki',
            cancelButtonText: '<i class="fa fa-times"></i> Batal',
            allowOutsideClick: false,
        }).then(function (result) {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Memproses Perbaikan',
                text: 'Mohon tunggu, sedang memperbarui data template keuangan...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function () {
                    Swal.showLoading();
                }
            });

            const postData = {};
            postData[csrf.name] = csrf.value;

            jQuery.post(url, postData)
                .done(function (res) {
                    if (res && res.status === 1) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message || 'Data template keuangan berhasil diperbaiki.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            timerProgressBar: true,
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', (res && res.message) ? res.message : 'Terjadi kesalahan saat memproses perbaikan.', 'error');
                    }
                })
                .fail(function (xhr) {
                    console.error('Gagal perbaiki:', xhr.status, xhr.responseText);
                    const res = xhr.responseJSON || {};
                    Swal.fire('Gagal', res.message || 'Terjadi kesalahan pada server saat memproses permintaan.', 'error');
                });
        });
    });
})();
</script>
@endif
