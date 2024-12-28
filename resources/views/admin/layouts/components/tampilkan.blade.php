@if ($link_berkas)
    <div class="modal-body">
        @if ($tipe == '.pdf')
            <iframe src="{{ $link_berkas }}" type="application/pdf" style="width: 100%; height: 300px;"></iframe>
        @else
            <img src="{{ $link_berkas }}" style="width: 100%; height: auto;">
        @endif
    </div>
    <div class="modal-footer">
        <div class="text-center">
            <a href="{{ $link_unduh }}" class="btn btn-flat bg-navy btn-sm"><i class="fa fa-download"></i> Unduh Dokumen</a>
        </div>
    </div>
@else
    <div class="modal-body">
        Berkas tidak ditemukan.
    </div>
@endif
