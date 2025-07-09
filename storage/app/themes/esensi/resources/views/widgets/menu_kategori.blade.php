<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fas fa-bars mr-1"></i>{{ $judul_widget }}</h3>
    </div>
    <div class="box-body content">
        <ul class="divide-y">
            @foreach ($menu_kiri as $data)
                <li><a href="{{ site_url('artikel/kategori/' . $data['slug']) }}" class="py-2 block">{{ $data['kategori'] }}</a>
                    @if (count($data['submenu'] ?? []) > 0)
                        <ul class="divide-y">
                            @foreach ($data['submenu'] as $submenu)
                                <li><a href="{{ site_url('artikel/kategori/' . $submenu['slug']) }}" class="py-2 block">{{ $submenu['kategori'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
