<div class="single_bottom_rightbar">
    <h2>
        <i class="fa fa-archive"></i> {{ $judul_widget }}
    </h2>
    <ul role="tablist" class="nav nav-tabs custom-tabs">
        <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home"
                href="#ekologi">Ekologi</a></li>
        <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages"
                href="#internet">Jaringan</a>
        </li>
        <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#status_adat">Status
                {{ ucwords(setting('sebutan_desa')) }}</a></li>
    </ul>
    <div class="tab-content">
        @foreach (['ekologi' => 'profil_ekologi', 'internet' => 'profil_internet', 'status_adat' => 'profil_status'] as $jenis => $jenis_profil)
            <div id="{{ $jenis }}" class="tab-pane fade in @if ($jenis == 'ekologi') active @endif" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            @foreach ($$jenis_profil as $profil)
                                <tr>
                                    <th style="width: 30%; text-align: left;">{{ SebutanDesa($profil->judul) }}</th>
                                    <td style="width: 5%; text-align: center;">:</td>
                                    <td>
                                        @php
                                            $isImageOrFile = in_array($profil->key, [
                                                'struktur_adat',
                                                'dokumen_regulasi_penetapan_kampung_adat',
                                            ]);
                                            $filePath = LOKASI_DOKUMEN . $profil['value'];
                                        @endphp

                                        @if (!empty($profil['value']) && file_exists($filePath) && $isImageOrFile)
                                            <a href="{{ base_url($filePath) }}" target="_blank"
                                                class="btn btn-sm btn-primary">
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
        @endforeach
    </div>
</div>
