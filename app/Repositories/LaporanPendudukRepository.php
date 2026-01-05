<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Repositories;

use App\Enums\JenisKelaminEnum;
use App\Enums\PeristiwaKeluargaEnum;
use App\Enums\PeristiwaPendudukEnum;
use App\Enums\PindahEnum;
use App\Enums\SHDKEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\Keluarga;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use Illuminate\Support\Carbon;

class LaporanPendudukRepository
{
    public static function dataPenduduk($tahun, $bulan)
    {
        // =================================================================================
        // 1. CALCULATE INITIAL STATE (START OF MONTH)
        // Use the end of the PREVIOUS month as the start of the current month.
        // =================================================================================
        $bulanLalu         = Carbon::create($tahun, $bulan)->subMonth();
        $pendudukAwalBulan = Penduduk::awalBulan($bulanLalu->format('Y'), $bulanLalu->format('m'))->get();
        $pendudukAwal      = [
            'WNI_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'KK_L'  => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
            'KK_P'  => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
        ];
        $pendudukAwal['KK'] = $pendudukAwal['KK_L'] + $pendudukAwal['KK_P'];

        // =================================================================================
        // 2. GET ALL MUTATIONS FOR THE CURRENT MONTH FROM LOGS
        // =================================================================================
        $mutasiPenduduk   = LogPenduduk::with(['penduduk' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)->get();
        $keluargaPenduduk = LogKeluarga::with(['keluarga.kepalaKeluarga' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)->get();

        // =================================================================================
        // 3. COUNT AND CATEGORIZE MUTATIONS
        // =================================================================================
        $kelahiran = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_LAHIR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_LAHIR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_LAHIR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_LAHIR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KELUARGA_BARU->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KELUARGA_BARU->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $kelahiran['KK'] = $kelahiran['KK_L'] + $kelahiran['KK_P'];

        $kematian = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::MATI->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::MATI->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::MATI->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::MATI->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $kematian['KK'] = $kematian['KK_L'] + $kematian['KK_P'];
        $pendatang      = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $pendatang['KK'] = $pendatang['KK_L'] + $pendatang['KK_P'];
        $pindah          = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $pindah['KK'] = $pindah['KK_L'] + $pindah['KK_P'];
        $hilang       = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::HILANG->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::HILANG->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::HILANG->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', PeristiwaPendudukEnum::HILANG->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->value)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $hilang['KK'] = $hilang['KK_L'] + $hilang['KK_P'];

        // =================================================================================
        // 4. CALCULATE FINAL STATE (END OF MONTH)
        // =================================================================================
        $pendudukAkhir = [
            'WNI_L' => $pendudukAwal['WNI_L'] + $kelahiran['WNI_L'] + $pendatang['WNI_L'] - $pindah['WNI_L'] - $hilang['WNI_L'] - $kematian['WNI_L'],
            'WNI_P' => $pendudukAwal['WNI_P'] + $kelahiran['WNI_P'] + $pendatang['WNI_P'] - $pindah['WNI_P'] - $hilang['WNI_P'] - $kematian['WNI_P'],
            'WNA_L' => $pendudukAwal['WNA_L'] + $kelahiran['WNA_L'] + $pendatang['WNA_L'] - $pindah['WNA_L'] - $hilang['WNA_L'] - $kematian['WNA_L'],
            'WNA_P' => $pendudukAwal['WNA_P'] + $kelahiran['WNA_P'] + $pendatang['WNA_P'] - $pindah['WNA_P'] - $hilang['WNA_P'] - $kematian['WNA_P'],
            // keluarga
            'KK_L' => $pendudukAwal['KK_L'] + $kelahiran['KK_L'] + $pendatang['KK_L'] - $pindah['KK_L'] - $hilang['KK_L'] - $kematian['KK_L'],
            'KK_P' => $pendudukAwal['KK_P'] + $kelahiran['KK_P'] + $pendatang['KK_P'] - $pindah['KK_P'] - $hilang['KK_P'] - $kematian['KK_P'],
        ];
        $pendudukAkhir['KK'] = $pendudukAkhir['KK_L'] + $pendudukAkhir['KK_P'];

        // =================================================================================
        // 5. OVERRIDE & CORRECTION FOR CURRENT MONTH
        // If this is the current month's report, the final number MUST match the real data.
        // We then back-calculate the 'awal' to ensure the report is consistent.
        // =================================================================================
        if (Carbon::create($tahun, $bulan)->isCurrentMonth()) {
            $pendudukAkhir['KK']   = Keluarga::statusAktif()->count();
            $pendudukAkhir['KK_L'] = Keluarga::statusAktif()->whereHas('kepalaKeluarga', static fn ($q) => $q->where('sex', JenisKelaminEnum::LAKI_LAKI))->count();
            $pendudukAkhir['KK_P'] = $pendudukAkhir['KK'] - $pendudukAkhir['KK_L'];

            // Recalculate `pendudukAwal` for KK to make the report consistent
            $pendudukAwal['KK_L'] = $pendudukAkhir['KK_L'] - ($kelahiran['KK_L'] + $pendatang['KK_L'] - $pindah['KK_L'] - $hilang['KK_L'] - $kematian['KK_L']);
            $pendudukAwal['KK_P'] = $pendudukAkhir['KK_P'] - ($kelahiran['KK_P'] + $pendatang['KK_P'] - $pindah['KK_P'] - $hilang['KK_P'] - $kematian['KK_P']);
            $pendudukAwal['KK']   = $pendudukAwal['KK_L'] + $pendudukAwal['KK_P'];
        }

        return [
            'kelahiran'      => $kelahiran,
            'kematian'       => $kematian,
            'pendatang'      => $pendatang,
            'pindah'         => $pindah,
            'hilang'         => $hilang,
            'penduduk_awal'  => $pendudukAwal,
            'penduduk_akhir' => $pendudukAkhir,
            'rincian_pindah' => self::rincian_pindah($mutasiPenduduk),
        ];
    }

    public static function sumberData($rincian, $tipe, $tahun = null, $bulan = null)
    {
        $data         = [];
        $keluarga     = ['kk', 'kk_l', 'kk_p'];
        $titlePeriode = strtoupper(getBulan($bulan)) . ' ' . $tahun;
        $filter       = [];

        switch($tipe) {
            case 'wni_l':
                $filter['sex']            = JenisKelaminEnum::LAKI_LAKI;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNI];
                break;

            case 'wni_p':
                $filter['sex']            = JenisKelaminEnum::PEREMPUAN;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNI];
                break;

            case 'wna_l':
                $filter['sex']            = JenisKelaminEnum::LAKI_LAKI;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
                break;

            case 'wna_p':
                $filter['sex']            = JenisKelaminEnum::PEREMPUAN;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
                break;

            case 'jml_l':
                $filter['sex'] = JenisKelaminEnum::LAKI_LAKI;
                break;

            case 'jml_p':
                $filter['sex'] = JenisKelaminEnum::PEREMPUAN;
                break;

            case 'kk':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                break;

            case 'kk_l':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                $filter['sex']      = JenisKelaminEnum::LAKI_LAKI;
                break;

            case 'kk_p':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                $filter['sex']      = JenisKelaminEnum::PEREMPUAN;
                break;
        }

        switch (strtolower($rincian)) {
            case 'awal':

                if (in_array($tipe, $keluarga, true)) {
                    $newKeluargaIds = LogKeluarga::whereIn('id_peristiwa', [PeristiwaKeluargaEnum::KELUARGA_BARU->value, PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value])
                        ->whereYear('tgl_peristiwa', $tahun)
                        ->whereMonth('tgl_peristiwa', $bulan)
                        ->pluck('id_kk');

                    $keluargaAktifQuery = Keluarga::statusAktif()
                        ->whereNotIn('id', $newKeluargaIds)
                        ->select('nik_kepala');

                    $data = [
                        'title' => 'PENDUDUK/KELUARGA AWAL BULAN ' . $titlePeriode,
                        'main'  => Penduduk::whereIn('id', $keluargaAktifQuery)
                            ->when(isset($filter['sex']), static fn ($q) => $q->whereSex($filter['sex']))
                            ->get(),
                    ];
                } else {
                    $bulanLalu = Carbon::create($tahun, $bulan)->subMonth();
                    $data      = [
                        'title' => 'PENDUDUK/KELUARGA AWAL BULAN ' . $titlePeriode,
                        'main'  => Penduduk::awalBulan($bulanLalu->format('Y'), $bulanLalu->format('m'))
                            ->when(isset($filter['warganegara_id']), static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))
                            ->when(isset($filter['sex']), static fn ($q) => $q->whereSex($filter['sex']))
                            ->get(),
                    ];
                }

                break;

            case 'lahir':
                $data = [
                    'title' => (in_array($tipe, $keluarga) ? 'KELUARGA BARU BULAN ' : 'KELAHIRAN BULAN ') . $titlePeriode,
                    'main'  => Penduduk::withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', PeristiwaKeluargaEnum::KELUARGA_BARU->value)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(PeristiwaPendudukEnum::BARU_LAHIR->value)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))
                        ->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'mati':
                $data = [
                    'title' => 'KEMATIAN BULAN ' . $titlePeriode,
                    'main'  => Penduduk::withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->value)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(PeristiwaPendudukEnum::MATI->value)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'datang':
                $data = [
                    'title' => 'PENDATANG BULAN ' . $titlePeriode,
                    'main'  => Penduduk::withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'pindah':
                $data = [
                    'title' => 'PINDAH/KELUAR PERGI BULAN ' . $titlePeriode,
                    'main'  => Penduduk::withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(PeristiwaPendudukEnum::PINDAH_KELUAR->value)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'hilang':
                $data = [
                    'title' => 'PENDUDUK HILANG BULAN ' . $titlePeriode,
                    'main'  => Penduduk::withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->value)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(PeristiwaPendudukEnum::HILANG->value)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'akhir':
                $is_kk_query      = in_array($tipe, $keluarga, true);
                $is_current_month = Carbon::create($tahun, $bulan)->isCurrentMonth();

                if ($is_kk_query && $is_current_month) {
                    // Special logic for KK on current month, to match index page
                    $keluargaAktif = Keluarga::statusAktif();

                    if (isset($filter['sex'])) {
                        $keluargaAktif->whereHas('kepalaKeluarga', static function ($q) use ($filter) {
                            $q->where('sex', $filter['sex']);
                        });
                    }

                    // Get the IDs of the heads of households
                    $kepalaKeluargaIds = $keluargaAktif->pluck('nik_kepala');
                    $query             = Penduduk::whereIn('id', $kepalaKeluargaIds);
                } else {
                    $akhirBulan             = Carbon::create($tahun, $bulan)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
                    $listKodePeristiwaAktif = array_diff(
                        array_keys(LogPenduduk::kodePeristiwa()),
                        [PeristiwaPendudukEnum::MATI->value, PeristiwaPendudukEnum::PINDAH_KELUAR->value, PeristiwaPendudukEnum::HILANG->value]
                    );

                    $query = Penduduk::query()
                        ->whereHas('log', static function ($q) use ($akhirBulan, $listKodePeristiwaAktif) {
                            $q->peristiwaSampaiDengan($akhirBulan)
                                ->whereIn('kode_peristiwa', $listKodePeristiwaAktif);
                        })
                        ->when(isset($filter['sex']), static fn ($q) => $q->where('sex', $filter['sex']))
                        ->when(isset($filter['kk_level']), static fn ($q) => $q->where('kk_level', $filter['kk_level']))
                        ->when(isset($filter['warganegara_id']), static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']));
                }

                $data = [
                    'title' => 'PENDUDUK/KELUARGA AKHIR BULAN ' . $titlePeriode,
                    'main'  => $query->get(),
                ];
                break;
        }

        return $data;
    }

    private static function rincian_pindah($mutasiPenduduk)
    {
        $data              = [];
        $data['DESA_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['DESA_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['DESA_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['DESA_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['KEC_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['KEC_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['KEC_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['KEC_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['KAB_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['KAB_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['KAB_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['KAB_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['PROV_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['PROV_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['PROV_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['PROV_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', PeristiwaPendudukEnum::PINDAH_KELUAR->value)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['TOTAL_L']    = $data['DESA_L'] + $data['KEC_L'] + $data['KAB_L'] + $data['PROV_L'];
        $data['TOTAL_P']    = $data['DESA_P'] + $data['KEC_P'] + $data['KAB_P'] + $data['PROV_P'];
        $data['TOTAL_KK_L'] = $data['DESA_KK_L'] + $data['KEC_KK_L'] + $data['KAB_KK_L'] + $data['PROV_KK_L'];
        $data['TOTAL_KK_P'] = $data['DESA_KK_P'] + $data['KEC_KK_P'] + $data['KAB_KK_P'] + $data['PROV_KK_P'];

        return $data;
    }
}
