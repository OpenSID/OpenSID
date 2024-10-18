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

use App\Models\AnggotaGrup;
use App\Models\DaftarKontak;
use App\Models\GrupKontak;
use App\Models\HubungWarga;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Sms extends Admin_Controller
{
    public $modul_ini           = 'hubung-warga';
    public $sub_modul_ini       = 'kirim-pesan';
    public $kategori_pengaturan = 'hubung warga';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('sms_model');
    }

    public function clear(): void
    {
        $this->session->per_page = 20;

        redirect('sms');
    }

    public function index($p = 1, $o = 6): void
    {
        $data['p']               = $p;
        $data['o']               = $o;
        $this->session->per_page = $this->input->post('per_page') ?? $this->session->per_page;

        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->sms_model->paging($p);
        $data['main']     = $this->sms_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);
        $data['navigasi'] = 'sms';

        $this->render('sms/manajemen_sms_table', $data);
    }

    public function outbox($p = 1, $o = 6): void
    {
        $data['p']               = $p;
        $data['o']               = $o;
        $this->session->per_page = $this->input->post('per_page') ?? $this->session->per_page;

        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->sms_model->paging_terkirim($p);
        $data['main']     = $this->sms_model->list_data_terkirim($o, $data['paging']->offset, $data['paging']->per_page);
        $data['navigasi'] = 'outbox';

        $this->render('sms/create_sms', $data);
    }

    public function sentitem($p = 1, $o = 6): void
    {
        $data['p']               = $p;
        $data['o']               = $o;
        $this->session->per_page = $this->input->post('per_page') ?? $this->session->per_page;

        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->sms_model->paging_terkirim($p);
        $data['main']     = $this->sms_model->list_data_terkirim($o, $data['paging']->offset, $data['paging']->per_page);
        $data['navigasi'] = 'sentitem';

        $this->render('sms/berita_terkirim', $data);
    }

    public function pending($p = 1, $o = 6): void
    {
        $data['p']               = $p;
        $data['o']               = $o;
        $this->session->per_page = $this->input->post('per_page') ?? $this->session->per_page;

        $data['per_page'] = $this->session->per_page;
        $data['paging']   = $this->sms_model->paging_tertunda($p);
        $data['main']     = $this->sms_model->list_data_tertunda($o, $data['paging']->offset, $data['paging']->per_page);
        $data['navigasi'] = 'pending';

        $this->render('sms/pesan_tertunda', $data);
    }

    public function form($tipe = '', $id = 0): void
    {
        isCan('u');

        $data['tipe']            = $tipe;
        $data['kontakPenduduk']  = Penduduk::select(['id', 'nama', 'telepon'])->whereNotNull('telepon')->status()->get();
        $data['kontakEksternal'] = DaftarKontak::select(['id_kontak', 'nama', 'telepon'])->whereNotNull('telepon')->get();

        if ($id) {
            $data['sms']         = $this->sms_model->get_sms($tipe, $id);
            $data['form_action'] = site_url("sms/insert/{$tipe}/{$id}");

            $this->load->view('sms/ajax_sms_form', $data);
        } else {
            $data['sms']         = null;
            $data['form_action'] = site_url("sms/insert/{$tipe}");

            $this->load->view('sms/ajax_sms_form_kirim', $data);
        }
    }

    public function broadcast(): void
    {
        $data['grupKontak']  = GrupKontak::withCount('anggota')->get();
        $data['form_action'] = site_url('sms/broadcast_proses');

        $this->load->view('sms/ajax_broadcast_form', $data);
    }

    public function broadcast_proses(): void
    {
        isCan('u');

        $post      = $this->input->post();
        $isi_pesan = htmlentities($post['TextDecoded']);

        // Ambil daftar anggota grup kontak
        $daftarAnggota = AnggotaGrup::where('id_grup', bilangan($post['id_grup']))->dataAnggota()->get();

        foreach ($daftarAnggota as $anggota) {
            $this->sms_model->sendBroadcast([
                'DestinationNumber' => $anggota->telepon,
                'TextDecoded'       => $isi_pesan,
            ]);
        }

        redirect('sms/outbox');
    }

    // Sms
    public function insert($tipe = '', $id = ''): void
    {
        isCan('u');

        if ($tipe == 3) {
            $this->sms_model->update($id);
            redirect('sms/pending');
        }

        $this->sms_model->insert();
        if ($tipe == 1) {
            redirect('sms');
        } elseif ($tipe == 2) {
            redirect('sms/sentitem');
        } else {
            redirect('sms/outbox');
        }
    }

    public function update($id = ''): void
    {
        isCan('u');

        $this->sms_model->update($id);
        redirect('sms');
    }

    public function delete($tipe = 0, $id = ''): void
    {
        isCan('h');

        $this->sms_model->delete($tipe, $id);
        if ($tipe == 1) {
            redirect('sms');
        } elseif ($tipe == 2) {
            redirect('sms/sentitem');
        } elseif ($tipe == 3) {
            redirect('sms/pending');
        } else {
            redirect('sms/outbox');
        }
    }

    public function deleteAll($tipe = 0): void
    {
        isCan('h');

        $this->sms_model->deleteAll($tipe);
        if ($tipe == 1) {
            redirect('sms');
        } elseif ($tipe == 2) {
            redirect('sms/sentitem');
        } elseif ($tipe == 3) {
            redirect('sms/pending');
        } else {
            redirect('sms/outbox');
        }
    }

    // Kirim Pesan (Hubung Warga)
    public function arsip()
    {
        return view('admin.sms.hubung_warga.index', [
            'navigasi' => 'arsip',
        ]);
    }

    public function arsipDatatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(HubungWarga::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    if (can('h')) {
                        return '<a href="#" data-href="' . ci_route('sms.hubungdelete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function kirim()
    {
        isCan('u');

        return view('admin.sms.hubung_warga.form', [
            'grupKontak' => GrupKontak::withCount('anggota')->get(),
            'formAction' => ci_route('sms.proseskirim'),
            'navigasi'   => 'kirim',
        ]);
    }

    public function prosesKirim(): void
    {
        isCan('u');

        $validasi = $this->hubungWargaValidate($this->request);

        // Kirim pesan berdasarkan cara hubung warga
        $notif = $this->kirimPesanGrup($validasi);

        if ($notif['jumlahBerhasil'] > 0) {
            HubungWarga::create($validasi);
            set_session('success', "Berhasil Kirim Pesan </br>{$notif['pesanError']}");
        } else {
            set_session('error', "Gagal Kirim Pesan </br>{$notif['pesanError']}");
        }

        redirect('sms/arsip');
    }

    // Hanya filter inputan
    protected function hubungWargaValidate($request = [])
    {
        return [
            'config_id'  => identitas('id'),
            'id_grup'    => bilangan($request['id_grup']),
            'subjek'     => htmlentities($request['subjek']),
            'isi'        => htmlentities($request['isi']),
            'created_by' => auth()->id,
            'updated_by' => auth()->id,
        ];
    }

    protected function kirimPesanGrup($data = [])
    {
        $this->load->library('OTP/OTP_manager', null, 'otp');

        $result        = [];
        $daftarAnggota = AnggotaGrup::where('id_grup', bilangan($data['id_grup']))->dataAnggota()->get();

        foreach ($daftarAnggota as $anggota) {
            // Kirim pesan berdasarkan pilihan hubung warga
            // Prioritas : berdasarkan pilihan, telegram jika tidak tersedia, jangan kirim
            switch (true) {
                case (bool) $this->setting->aktifkan_sms && $anggota->hubung_warga = 'SMS' && null !== $anggota->telepon:
                    $kirim                                                         = $this->sms_model->sendBroadcast([
                        'DestinationNumber' => $anggota->telepon,
                        'TextDecoded'       => <<<EOD
                            SUBJEK :
                            {$data['subjek']}

                            ISI :
                            {$data['isi']}
                            EOD,
                    ]);

                    if ($kirim) {
                        $result['jumlahBerhasil']++;
                        break;
                    }

                    $result['pesanError'] = "Gagal kirim pesan SMS ke : {$anggota->nama} </br>";

                    // no break
                case $anggota->hubung_warga = 'Email' && null !== $anggota->email:
                    try {
                        $kirim = $this->otp->driver('email')->kirim_pesan([
                            'tujuan' => $anggota->email,
                            'subjek' => $data['subjek'],
                            'isi'    => $data['isi'],
                            'nama'   => $anggota->nama,
                        ]);
                        $result['jumlahBerhasil']++;

                        break;
                    } catch (Exception $e) {
                        log_message('error', $e);
                        $result['pesanError'] = "Gagal kirim pesan Email ke : {$anggota->nama} </br>";
                    }

                default:
                    try {
                        $kirim = $this->otp->driver('telegram')->kirim_pesan([
                            'tujuan' => $anggota->telegram,
                            'subjek' => $data['subjek'],
                            'isi'    => $data['isi'],
                        ]);
                        $result['jumlahBerhasil']++;
                    } catch (Exception $e) {
                        log_message('error', $e);
                        $result['pesanError'] = "Gagal kirim pesan Telegram ke : {$anggota->nama} </br>";
                    }
                    break;
            }
        }

        $result['jumlahData'] = count($daftarAnggota);

        return $result;
    }

    public function hubungDelete($id = null): void
    {
        isCan('h');

        if (HubungWarga::destroy($this->request['id_cb'] ?? $id)) {
            set_session('success', 'Berhasil Hapus Data');
        } else {
            set_session('error', 'Gagal Hapus Data');
        }

        redirect('sms/arsip');
    }
}
