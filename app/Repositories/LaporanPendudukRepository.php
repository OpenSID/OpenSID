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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Repositories;

use App\Enums\JenisKelaminEnum;
use App\Enums\PindahEnum;
use App\Enums\SHDKEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use Illuminate\Support\Carbon;

class LaporanPendudukRepository
{
    public static function dataPenduduk($tahun, $bulan)
    {
        $bulanDepan        = Carbon::create($tahun, $bulan)->addMonth();
        $bulanFix          = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $pendudukAwalBulan = Penduduk::awalBulan($bulanDepan->format('Y'), $bulanFix)->get();
        $pendudukAwal      = [
            'WNI_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
            'KK_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
        ];
        $mutasiPenduduk = LogPenduduk::with(['penduduk' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)->get();
        // KELUARGA_BARU_DATANG
        $keluargaPenduduk = LogKeluarga::with(['keluarga.kepalaKeluarga' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)->get();

        $kelahiran = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];

        $pendudukAwal['KK_L'] = $pendudukAwal['KK_L'] - $kelahiran['KK_L'];
        $pendudukAwal['KK_P'] = $pendudukAwal['KK_P'] - $kelahiran['KK_P'];
        $pendudukAwal['KK']   = $pendudukAwal['KK_L'] + $pendudukAwal['KK_P'];

        $kelahiran['KK'] = $kelahiran['KK_L'] + $kelahiran['KK_P'];
        $kematian        = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $kematian['KK'] = $kematian['KK_L'] + $kematian['KK_P'];
        $pendatang      = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU_DATANG)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU_DATANG)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $pendatang['KK'] = $pendatang['KK_L'] + $pendatang['KK_P'];
        $pindah          = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $pindah['KK'] = $pindah['KK_L'] + $pindah['KK_P'];
        $hilang       = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
        $hilang['KK']  = $hilang['KK_L'] + $hilang['KK_P'];
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

    private static function rincian_pindah($mutasiPenduduk)
    {
        $data              = [];
        $data['DESA_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['DESA_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['DESA_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['DESA_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['KEC_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['KEC_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['KEC_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['KEC_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['KAB_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['KAB_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['KAB_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['KAB_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['PROV_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->count();
        $data['PROV_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->count();
        $data['PROV_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['PROV_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['TOTAL_L']    = $data['DESA_L'] + $data['KEC_L'] + $data['KAB_L'] + $data['PROV_L'];
        $data['TOTAL_P']    = $data['DESA_P'] + $data['KEC_P'] + $data['KAB_P'] + $data['PROV_P'];
        $data['TOTAL_KK_L'] = $data['DESA_KK_L'] + $data['KEC_KK_L'] + $data['KAB_KK_L'] + $data['PROV_KK_L'];
        $data['TOTAL_KK_P'] = $data['DESA_KK_P'] + $data['KEC_KK_P'] + $data['KAB_KK_P'] + $data['PROV_KK_P'];

        return $data;
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
                $data = [
                    'title' => 'PENDUDUK/KELUARGA AWAL BULAN ' . $titlePeriode,
                    'main'  => Penduduk::awalBulan($tahun, $bulan)->when($filter['kk_level'], static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk'))->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
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
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::BARU_LAHIR)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
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
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::MATI)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
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
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KELUARGA_BARU_DATANG)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::BARU_PINDAH_MASUK)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
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
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::PINDAH_KELUAR)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
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
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::HILANG)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'akhir':
                $bulanDepan = Carbon::createFromDate($tahun, $bulan)->addMonth();
                $data       = [
                    'title' => 'PENDUDUK/KELUARGA AKHIR BULAN ' . $titlePeriode,
                    'main'  => Penduduk::awalBulan($bulanDepan->format('Y'), $bulanDepan->format('m'))->when($filter['kk_level'], static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk'))->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;
        }

        return $data;
    }
}
