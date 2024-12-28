@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_bottom_rightbar">
    <h2><i class="fa fa-globe"></i>&ensp;{{ $judul_widget }}</h2>
    <div class="box-body">
        @foreach ($sosmed as $data)
            @if (!empty($data['link']))
                <a href="{{ $data['link'] }}" rel="noopener noreferrer" target="_blank">
                    <img src="{{ $data['icon'] }}" alt="{{ $data['nama'] }}" style="width:50px;height:50px;" />
                </a>
            @endif
        @endforeach
    </div>
</div>
