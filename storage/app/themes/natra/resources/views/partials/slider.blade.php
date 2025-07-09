<div class="slick_slider" style="margin-bottom:5px;">
    @php $active = true; @endphp
    @foreach ($slider_gambar['gambar'] as $gambar)
        @php $file_gambar = $slider_gambar['lokasi'] . 'sedang_' . $gambar['gambar']; @endphp
        @if (is_file($file_gambar))
            <div class="single_iteam {{ $active ? 'active' : '' }}" data-artikel="{{ $gambar['id'] }}" @if ($slider_gambar['sumber'] != 3) onclick="location.href='{{ ci_route('artikel.' . buat_slug($gambar)) }}'" @endif>
                <img class="tlClogo" src="{{ ci_route("{$slider_gambar['lokasi']}sedang_{$gambar['gambar']}") }}">
                <div class="{{ $gambar['judul'] ? 'textgambar' : '' }} hidden-xs">{{ $gambar['judul'] }}</div>
            </div>
            @php $active = false; @endphp
        @endif
    @endforeach
</div>
<script>
    $('.tlClogo').bind('contextmenu', function(e) {
        return false;
    });
</script>
