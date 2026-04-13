@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

@if (config_item('kode_kota'))
    <script>
        const KODE_KOTA = "{{ config_item('kode_kota') }}";
        const TANGGAL = "{{ date('Y-m-d') }}";
        const BESOK = "{{ date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') + 1, date('Y'))) }}";
    </script>
    <script src="{{ theme_asset('js/widget.min.js') }}"></script>

    <div class="archive_style_1">
        <div class="single_bottom_rightbar">
            <h2 class="box-title"><i class="fa fa-calendar"></i>&ensp;{{ $judul_widget }}</h2>
            <div class="data-case-container">
                <ul class="ants-right-headline">
                    <li class="info-date"><span data-name="kota"><i class="fa fa-spinner fa-pulse"></i></span></li>
                    <li class="info-case">
                        <table style="width: 100%;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td id="jadwal-shalat" colspan="3" class="description">
                    <li class="info-region"><span data-name="tanggal"><i class="fa fa-spinner fa-pulse"></i></span></li>
                    </td>
                    <td id="jadwal-shalat2" colspan="3" class="description">
                        <li class="info-region"><span data-name="tanggal2"><i class="fa fa-spinner fa-pulse"></i></span>
                        </li>
                    </td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Imsak</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="imsak"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Imsak</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="imsak2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Subuh</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="subuh"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Subuh</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="subuh2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Terbit</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="terbit"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Terbit</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="terbit2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Dhuha</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="dhuha"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Dhuha</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="dhuha2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Dzuhur</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="dzuhur"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Dzuhur</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="dzuhur2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Ashar</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="ashar"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Ashar</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="ashar2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Maghrib</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="maghrib"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Maghrib</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="maghrib2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    <tr>
                        <td id="jadwal-shalat" class="description">Isya</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="isya"><i class="fa fa-spinner fa-pulse"></i></span></td>
                        <td id="jadwal-shalat2" class="description">Isya</td>
                        <td class="dot">:</td>
                        <td class="case"><span data-name="isya2"><i class="fa fa-spinner fa-pulse"></i></span></td>
                    </tr>
                    </table>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif
