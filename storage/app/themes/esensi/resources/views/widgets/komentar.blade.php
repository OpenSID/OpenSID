<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-comments mr-2 mr-1"></i>{{ $judul_widget }}</h3>
    </div>
    <div class="box-body">
        <div class="marquee-vertical-wrapper" style="height:150px;">
            <div class="marquee-track-vertical">
                @for ($i = 0; $i < 2; $i++)
                    <ul class="divide-y">
                        @foreach ($komen as $data)
                            <li class="py-2 space-y-2">
                                <blockquote class="italic">{{ potong_teks($data['komentar'], 50) }}</blockquote>... <a href="{{ site_url('artikel/' . buat_slug($data)) }}" class="text-link">selengkapnya</a>
                                <p class="text-xs lg:text-sm"><i class="fas fa-comment"></i> {{ $data['owner'] }}</p>
                                <p class="text-xs lg:text-sm">{{ tgl_indo2($data['tgl_upload']) }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endfor
            </div>
        </div>
    </div>
</div>
