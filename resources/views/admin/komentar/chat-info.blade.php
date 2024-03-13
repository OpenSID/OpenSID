<div class="direct-chat-msg {{ $parent_id ? 'right' : 'left' }}">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-right">{{ $pengguna }}</span>
        <span class="direct-chat-timestamp pull-left">{{ tgl_indo2($tgl_upload) }}</span>
    </div>
    <img class="direct-chat-img" src="{{ $foto }}" alt="message user image">
    <div class="direct-chat-text">
        {{ $komentar }}
    </div>
</div>
@foreach($children as $child)
    <div class="direct-chat-msg {{ $child['parent_id'] ? 'right' : 'left' }}">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-right">{{ $child['pengguna'] }}</span>
        <span class="direct-chat-timestamp pull-left">{{ tgl_indo2($child['tgl_upload']) }}</span>
    </div>
    <img class="direct-chat-img" src="{{ $child['foto'] }}" alt="message user image">
    <div class="direct-chat-text">
        {{ $child['komentar'] }}
    </div>
</div>
@endforeach