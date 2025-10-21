<div class="direct-chat-msg {{ $parent_id ? 'right' : 'left' }}">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-right">{{ $pengguna['nama'] }}<code>({{ $pengguna['level'] }})</code></span>
        <span class="direct-chat-timestamp pull-left">{{ tgl_indo2($tgl_upload) }}</span>
    </div>
    <img class="direct-chat-img" src="{{ $foto }}" alt="message user image">
    <div class="direct-chat-text">
        {{ $komentar }}
        @if (!$parent_id)
        <div class="tools" style="margin-top: 10px;">
            <x-hapus-button :confirmDelete="true" :url="ci_route('komentar.delete', ['parent_id' => $parent_id, 'id' => $id])" />
        </div>
        @endif
    </div>
</div>
