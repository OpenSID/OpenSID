<div class="direct-chat-msg {{ $parent_id ? 'right' : 'left' }}">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-right">{{ $pengguna['nama'] }}<code>({{ $pengguna['level'] }})</code></span>
        <span class="direct-chat-timestamp pull-left">{{ tgl_indo2($tgl_upload) }}</span>
    </div>
    <img class="direct-chat-img" src="{{ $foto }}" alt="message user image">
    <div class="direct-chat-text">
        {{ $komentar }}
        @if ($parent_id)
            <div class="tools" style="margin-top: 10px;">
                <a href="#" data-href="{{ ci_route("komentar.delete/{$parent_id}/{$id}") }}" class="btn btn-danger btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o" style="color: rgb(255, 255, 255);"></i></a>
            </div>
        @endif
    </div>
</div>
