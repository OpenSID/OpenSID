<div class="tab-pane active">
    <div class="callout callout-info">
        <p style="margin-bottom:0">
            <i class="fa fa-flask"></i> <strong>Riwayat ambil/lepas (get/release) marketplace lokal.</strong>
            Setiap pengajuan/pemasangan (get) dan penghapusan (release) paket lokal tercatat di sini.
        </p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="padat">No</th>
                                <th>Paket</th>
                                <th>Aksi</th>
                                <th>Strategi / Ref</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesanan as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $p['name'] ?? '-' }}</strong></td>
                                    <td>
                                        @if (($p['status'] ?? '') === 'terpasang')
                                            <span class="label label-success"><i class="fa fa-download"></i> Ambil (pasang)</span>
                                        @elseif (($p['status'] ?? '') === 'dihapus')
                                            <span class="label label-danger"><i class="fa fa-trash"></i> Lepas (hapus)</span>
                                        @else
                                            <span class="label label-default">{{ $p['status'] ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $p['strategy'] ?? '-' }}</code>@if (($p['strategy'] ?? '') === 'git-archive') / <code>{{ $p['ref'] ?? 'HEAD' }}</code>@if (! empty($p['fetch'])) <span class="label label-info">fetch</span>@endif @endif
                                    </td>
                                    <td>{{ $p['waktu'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="alert alert-warning" style="margin:10px">
                                            Belum ada riwayat. Ajukan paket di tab <strong>Form Pendaftaran</strong> atau pasang dari
                                            <strong>Paket Tersedia</strong>.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
