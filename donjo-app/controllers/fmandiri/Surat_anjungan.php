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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\SyaratSurat;

class Surat_anjungan extends Mandiri_Controller
{
    public function buat($id = '')
    {
        $id_pend    = $this->is_login->id_pend;
        $permohonan = [];
        // Cek hanya status = 0 (belum lengkap) yg boleh di ubah
        if ($id) {
            $obj = PermohonanSurat::where(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0])->first();

            if (! $obj) {
                redirect('layanan-mandiri/surat_anjungan/buat');
            }
            $permohonan  = $obj->toArray();
            $form_action = ci_route("layanan-mandiri/surat/form/{$id}");
        } else {
            $form_action = ci_route('layanan-mandiri/surat/form');
        }

        $data = [
            'penduduk_login'       => Penduduk::find($id_pend),
            'menu_surat_mandiri'   => FormatSurat::kunci(0)->mandiri()->get(),
            'menu_dokumen_mandiri' => SyaratSurat::get()->toArray(),
            'permohonan'           => $permohonan,
            'form_action'          => $form_action,
        ];

        return view('layanan_mandiri.surat.buat', $data);
    }

    public function form($id = '')
    {
        $id_pend = $this->is_login->id_pend;

        $surat        = FormatSurat::find($id);
        $syarat_surat = $this->getSyarat($surat->syarat_surat);
        $penduduk     = Penduduk::find($id_pend) ?? show_404();
        $individu     = $penduduk->formIndividu();
        $data         = [];
        $data         = array_merge($data, [
            'penduduk_login' => $penduduk,
            'syarat_surat'   => $syarat_surat,
            'url'            => $surat->url_surat,
            'individu'       => $individu,
            'anggota'        => $penduduk?->keluarga?->anggota?->toArray(),
            'surat_url'      => rtrim($_SERVER['REQUEST_URI'], '/clear'),
            'form_action'    => ci_route("surat/cetak/{$surat->url_surat}"),
            'cek_anjungan'   => $this->cek_anjungan,
            'mandiri'        => 1,
            'kembali'        => 'Layanan Surat',
        ]);
        $this->get_data_untuk_form($surat->url_surat, $data);

        return view('layanan_mandiri.surat.form', $data);
    }

    public function getSyarat($suratMaster)
    {
        $syaratSurat = SyaratSurat::query()->get();

        $data = [];

        $syaratSuratList = json_decode($suratMaster, true);

        foreach ($syaratSurat as $baris) {
            if (is_array($syaratSuratList) && in_array($baris->ref_syarat_id, $syaratSuratList)) {

                $data[] = $baris->ref_syarat_nama;
            }
        }

        return $data;
    }

    private function get_data_untuk_form($url, array &$data): void
    {
        // Panggil 1 penduduk berdasarkan datanya sendiri
        $data['penduduk'] = [$data['periksa']['penduduk']];

        $data['surat_terakhir']     = LogSurat::lastNomerSurat($url);
        $data['surat']              = FormatSurat::where('url_surat', $url)->first()->toArray();
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = FormatSurat::format_penomoran_surat($data);
    }
}
