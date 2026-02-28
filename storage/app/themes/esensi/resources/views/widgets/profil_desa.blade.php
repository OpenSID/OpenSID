<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fas fa-folder-open mr-1"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="box-body" style="padding-top: .1rem;">
        <ul class="nav nav-tabs flex list-none border-b-0 pl-0 mb-4" id="tab-arsip" role="tablist">
            <li class="nav-item flex-grow text-center active" role="presentation">
                <a
                    href="#ekologi"
                    class="nav-link w-full block font-medium border-x-0 border-t-0 border-b-2 border-transparent px-4 py-3 my-2 hover:border-transparent hover:bg-gray-100 focus:border-transparent active"
                    data-bs-toggle="pill"
                    data-bs-target="#ekologi"
                    role="tab"
                    aria-controls="ekologi"
                    aria-selected="true"
                    data-toggle="tab"
                >Ekologi</a>
            </li>
            <li class="nav-item flex-grow text-center" role="presentation">
                <a
                    href="#internet"
                    class="nav-link w-full block font-medium border-x-0 border-t-0 border-b-2 border-transparent px-4 py-3 my-2 hover:border-transparent hover:bg-gray-100 focus:border-transparent"
                    data-bs-toggle="pill"
                    data-bs-target="#internet"
                    role="tab"
                    aria-controls="internet"
                    aria-selected="false"
                >Internet</a>
            </li>
            <li class="nav-item flex-grow text-center" role="presentation">
                <a
                    href="#status_adat"
                    class="nav-link w-full block font-medium border-x-0 border-t-0 border-b-2 border-transparent px-4 py-3 my-2 hover:border-transparent hover:bg-gray-100 focus:border-transparent"
                    data-bs-toggle="pill"
                    data-bs-target="#status_adat"
                    role="tab"
                    aria-controls="status_adat"
                    aria-selected="false"
                >Status {{ ucwords(setting('sebutan_desa')) }}</a>
            </li>
        </ul>

        <div class="tab-content">
            @foreach (['ekologi' => 'profil_ekologi', 'internet' => 'profil_internet', 'status_adat' => 'profil_status'] as $jenis => $jenis_profil)
                <div id="{{ $jenis }}" class="tab-pane fade @if ($jenis == 'ekologi') show active @endif" role="tabpanel">
                    <div class="divide-y">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm text-sm">
                                <tbody>
                                    @foreach ($$jenis_profil as $profil)
                                    <tr>
                                        <th style="width: 30%; text-align: left;">{{ $profil->judul }}</th>
                                        <td style="width: 5%; text-align: center;">:</td>
                                        <td>
                                            @php
                                            $isImageOrFile = in_array($profil->key, ['struktur_adat', 'dokumen_regulasi_penetapan_kampung_adat']);
                                            $fileExists = !empty($profil['value']) && file_exists(LOKASI_DOKUMEN . $profil['value']);
                                            @endphp
                                            
                                            @if ($fileExists && $isImageOrFile)
                                            <a href="{{ base_url(LOKASI_DOKUMEN . $profil['value']) }}" target="_blank"
                                                class="btn btn-sm btn-primary px-2 py-0 text-xs">
                                                <i class="fa fa-eye"></i> Lihat
                                            </a>
                                            @else
                                            {{ $isImageOrFile ? '-' : $profil['value'] }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
