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

use App\Enums\JenisKelaminEnum;
use App\Enums\PindahEnum;
use App\Enums\SHDKEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Pamong;
use App\Models\Penduduk;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan extends Admin_Controller
{
    public $modul_ini           = 'statistik';
    public $sub_modul_ini       = 'laporan-bulanan';
    public $kategori_pengaturan = 'Data Lengkap';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function clear(): void
    {
        session_error_clear();
        $this->session->unset_userdata(['cari']);
        $this->session->bulanku  = date('n');
        $this->session->tahunku  = date('Y');
        $this->session->per_page = 200;

        redirect('laporan');
    }

    public function index(): void
    {

        if (isset($this->session->bulanku)) {
            $data['bulanku'] = $this->session->bulanku;
        } else {
            $data['bulanku']        = date('n');
            $this->session->bulanku = $data['bulanku'];
        }

        if (isset($this->session->tahunku)) {
            $data['tahunku'] = $this->session->tahunku;
        } else {
            $data['tahunku']        = date('Y');
            $this->session->tahunku = $data['tahunku'];
        }

        $data['bulan']                = $data['bulanku'];
        $data['tahun']                = $data['tahunku'];
        $data['data_lengkap']         = true;
        $data['sesudah_data_lengkap'] = true;
        $tanggal_lengkap              = LogPenduduk::min('tgl_lapor');
        $dataLengkap                  = data_lengkap();
        if (! $dataLengkap) {
            $data['data_lengkap'] = false;
            view('admin.laporan.bulanan', $data);

            return;
        }

        $tahun_bulan = (new DateTime($tanggal_lengkap))->format('Y-m');
        if ($tahun_bulan > $data['tahunku'] . '-' . $data['bulanku']) {
            $data['sesudah_data_lengkap'] = false;
            view('admin.laporan.bulanan', $data);

            return;
        }

        $this->session->tgl_lengkap = $tanggal_lengkap;
        $data['tgl_lengkap']        = $tanggal_lengkap;
        $data['tahun_lengkap']      = (new DateTime($tanggal_lengkap))->format('Y');
        $dataPenduduk               = $this->data_penduduk($data['tahun'], $data['bulan']);

        view('admin.laporan.bulanan', array_merge($data, $dataPenduduk));
    }

    private function data_penduduk($tahun, $bulan)
    {
        $pendudukAwalBulan = Penduduk::awalBulan($tahun, $bulan)->get();
        $pendudukAwal      = [
            'WNI_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::LAKI_LAKI)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
            'KK_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::PEREMPUAN)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
        ];
        $pendudukAwal['KK'] = $pendudukAwal['KK_L'] + $pendudukAwal['KK_P'];
        $mutasiPenduduk     = LogPenduduk::with(['penduduk' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)->get();
        $keluargaPenduduk   = LogKeluarga::with(['keluarga.kepalaKeluarga' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)->get();

        $kelahiran = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::LAKI_LAKI)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::PEREMPUAN)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
        ];
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
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::LAKI_LAKI)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::PEREMPUAN)->count(),
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
            'rincian_pindah' => $this->rincian_pindah($mutasiPenduduk),
        ];
    }

    private function rincian_pindah($mutasiPenduduk)
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

    public function dialog(string $aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = 'Cetak';
        $data['form_action'] = ci_route('laporan.cetak', $aksi);
        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function cetak(string $aksi = 'cetak'): void
    {
        $data = $this->data_cetak();
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=Laporan_bulanan_' . date('d_m_Y') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.laporan.bulanan_print', $data);
    }

    private function data_cetak()
    {
        $data               = [];
        $data['bulan']      = $this->session->bulanku;
        $data['tahun']      = $this->session->tahunku;
        $data['bln']        = getBulan($data['bulan']);
        $data['pamong_ttd'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $dataPenduduk       = $this->data_penduduk($data['tahun'], $data['bulan']);

        return array_merge($data, $dataPenduduk);
    }

    public function bulan(): void
    {
        $bulanku = $this->input->post('bulan');
        if ($bulanku != '') {
            $this->session->bulanku = $bulanku;
        } else {
            unset($this->session->bulanku);
        }

        $tahunku = $this->input->post('tahun');
        if ($tahunku != '') {
            $this->session->tahunku = $tahunku;
        } else {
            unset($this->session->tahunku);
        }
        redirect('laporan');
    }

    public function detail_penduduk($rincian, $tipe): void
    {
        $data            = $this->sumberData($rincian, $tipe);
        $data['rincian'] = $rincian;
        $data['tipe']    = $tipe;
        view('admin.laporan.detail.index', $data);
    }

    private function sumberData($rincian, $tipe)
    {
        $data         = [];
        $keluarga     = ['kk', 'kk_l', 'kk_p'];
        $tahun        = $this->session->tahunku;
        $bulan        = $this->session->bulanku;
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

    public function detail_dialog($aksi = 'cetak', $rincian = 'awal', $tipe = 'wni_l')
    {
        $data                = $this->modal_penandatangan();
        $data['sensor_nik']  = true;
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = ci_route("laporan.detail_cetak.{$aksi}.{$rincian}.{$tipe}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function detail_cetak($aksi = 'cetak', $rincian = 'awal', $tipe = 'wni_l')
    {
        $sumberData             = $this->sumberData($rincian, $tipe);
        $sumberData['file']     = $sumberData['title'];
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['isi']            = 'admin.laporan.detail.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];
        $data['sensor_nik']     = $this->input->post('sensor_nik') == 'on' ? 1 : false;

        view('admin.layouts.components.format_cetak', array_merge($data, $sumberData));
    }
}
