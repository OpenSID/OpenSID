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

class AnjunganSurat extends Mandiri_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (! $this->session->is_anjungan) {
            redirect(route('layanan-mandiri.beranda.index'));
        }
    }

    public function buat($id = '')
    {
        $id_pend    = $this->is_login->id_pend;
        $permohonan = [];
        // Cek hanya status = 0 (belum lengkap) yg boleh di ubah
        if ($id) {
            $obj = PermohonanSurat::where(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0])->first();

            if (! $obj) {
                redirect(route('anjungan.surat.buat'));
            }
            $permohonan  = $obj->toArray();
            $form_action = route('anjungan.surat.form', $id);
        } else {
            $form_action = route('anjungan.surat.form');
        }

        $data = [
            'menu_surat_mandiri'   => FormatSurat::kunci(FormatSurat::KUNCI_DISABLE)->mandiri()->get(),
            'menu_dokumen_mandiri' => SyaratSurat::get()->toArray(),
            'permohonan'           => $permohonan,
            'form_action'          => $form_action,
        ];

        return view('layanan_mandiri.anjungan.surat.buat', $data);
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
            'anjungan'       => true,
            'kembali' => 'Layanan Surat'
        ]);
        $this->get_data_untuk_form($surat->url_surat, $data);

        return view('layanan_mandiri.anjungan.surat.form', $data);
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

    public function kirim($id = ''): void
    {
        $this->load->library('Telegram/telegram');
        $post = $this->input->post();

        $surat = FormatSurat::where('url_surat', $post['url_surat'])->first();

        $syrat = collect(json_decode($surat->syarat_surat, true))
            ->mapWithKeys(fn($item, $key) => [(string)($key + 1) => $item])
            ->all();

        $data = [
            'config_id'   => identitas('id'),
            'id_pemohon'  => bilangan($post['nik']),
            'id_surat'    => $surat->id,
            'isian_form'  => json_encode($post, JSON_THROW_ON_ERROR),
            'status'      => 1, // Selalu 1 bagi pengguna layanan mandiri
            'keterangan'  => 'Permohonan Surat dari Anjungan Mandiri',
            'no_hp_aktif' => bilangan($post['no_hp_aktif']),
            'syarat'      => json_encode($syrat, JSON_THROW_ON_ERROR),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($id) {
            PermohonanSurat::whereId($id)->update($data);
        } else {
            $data['created_at'] = $data['updated_at'];

            PermohonanSurat::insert($data);

            if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                try {
                    // Data pesan telegram yang akan digantikan
                    $pesanTelegram = [
                        '[nama_penduduk]' => $this->is_login->nama,
                        '[judul_surat]'   => FormatSurat::find($post['id_surat'])->nama,
                        '[tanggal]'       => tgl_indo2(date('Y-m-d H:i:s')),
                        '[melalui]'       => 'Layanan Mandiri',
                        '[website]'       => APP_URL,
                    ];

                    $kirimPesan = setting('notifikasi_pengajuan_surat');
                    $kirimPesan = str_replace(array_keys($pesanTelegram), array_values($pesanTelegram), $kirimPesan);
                    $this->telegram->sendMessage([
                        'text'       => $kirimPesan,
                        'parse_mode' => 'Markdown',
                        'chat_id'    => $this->setting->telegram_user_id,
                    ]);
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                }
            }
        }

        $this->session->unset_userdata('data_permohonan');

        redirect(route('anjungan.permohonan'));
    }

    public function permohonan()
    {
        dd('permohonan');
    }
}
