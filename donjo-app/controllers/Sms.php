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

use App\Libraries\OTP\OtpManager;
use App\Models\AnggotaGrup;
use App\Models\DaftarKontak;
use App\Models\GrupKontak;
use App\Models\HubungWarga;
use App\Models\Inbox;
use App\Models\Outbox;
use App\Models\Penduduk;
use App\Models\SentItem;

defined('BASEPATH') || exit('No direct script access allowed');

class Sms extends Admin_Controller
{
    public $modul_ini           = 'hubung-warga';
    public $sub_modul_ini       = 'kirim-pesan';
    public $kategori_pengaturan = 'hubung warga';
    private OtpManager $otp;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->otp = new OtpManager();
    }

    public function index()
    {
        return view('admin.sms.inbox.index', [
            'navigasi' => 'inbox',
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(Inbox::with(['penduduk', 'kontak']))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->ID . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '<a href="' . ci_route('sms.form.1', $row->ID) . '" class="btn bg-orange btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Lihat Pesan" title="Tampilkan dan Balas"><i class="fa fa-reply"></i></a> ';
                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('sms.delete.1', $row->ID) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })->addColumn('nama', static fn ($row) => $row->kontak?->nama ?? ($row->penduduk?->nama ?? ''))
                ->editColumn('ReceivingDateTime', static fn ($row) => tgl_indo2($row->ReceivingDateTime))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($tipe = 'inbox', $id = 0): void
    {
        isCan('u');

        $data['tipe']            = $tipe;
        $data['kontakPenduduk']  = Penduduk::select(['id', 'nama', 'telepon'])->whereNotNull('telepon')->status()->get();
        $data['kontakEksternal'] = DaftarKontak::select(['id_kontak', 'nama', 'telepon'])->whereNotNull('telepon')->get();

        if ($id) {
            switch($tipe) {
                case 2:
                    $sms = SentItem::findOrFail($id);
                    break;

                case 1:
                    $sms = Inbox::selectRaw('SenderNumber AS DestinationNumber,TextDecoded')->findOrFail($id);
                    break;

                default:
                    $sms = Outbox::findOrFail($id);

            }
            $data['sms']         = $sms;
            $data['form_action'] = ci_route("sms.insert.{$tipe}.{$id}");

            view('admin.sms.ajax_sms_form', $data);
        } else {
            $data['sms']         = null;
            $data['form_action'] = ci_route("sms.insert.{$tipe}");

            view('admin.sms.ajax_sms_form_kirim', $data);
        }
    }

    public function broadcast(): void
    {
        $data['grupKontak']  = GrupKontak::withCount('anggota')->get();
        $data['form_action'] = ci_route('sms.broadcast_proses');

        view('admin.sms.ajax_broadcast_form', $data);
    }

    public function broadcast_proses(): void
    {
        isCan('u');

        $post      = $this->input->post();
        $isi_pesan = htmlentities((string) $post['TextDecoded']);

        // Ambil daftar anggota grup kontak
        $daftarAnggota = AnggotaGrup::where('id_grup', bilangan($post['id_grup']))->dataAnggota()->get();

        foreach ($daftarAnggota as $anggota) {
            Outbox::create([
                'DestinationNumber' => $anggota->telepon,
                'TextDecoded'       => $isi_pesan,
            ]);
        }

        redirect_with('success', 'Data berhasil disimpan', ci_route('sms.outbox'));
    }

    // Sms
    public function insert($tipe = '', $id = ''): void
    {
        isCan('u');

        if ($tipe == 3) {
            $data = ['TextDecoded' => htmlentities($this->request['TextDecoded'])];
            Outbox::where('id', $id)->update($data);
            redirect_with('success', 'Data berhasil disimpan', ci_route('sms.pending'));
        }

        $data = ['DestinationNumber' => bilangan($this->request['DestinationNumber']), 'TextDecoded' => htmlentities($this->request['TextDecoded'])];
        Outbox::create($data);

        if ($tipe == 1) {
            redirect('sms');
        } elseif ($tipe == 2) {
            redirect(ci_route('sms.sentitem'));
        } else {
            redirect(ci_route('sms.outbox'));
        }
    }

    public function update($id = ''): void
    {
        isCan('u');
        $data = ['TextDecoded' => htmlentities($this->request['TextDecoded'])];
        Outbox::where('id', $id)->update($data);
        redirect_with('success', 'Data berhasil disimpan', ci_route('sms'));
    }

    public function delete($tipe = 0, $id = ''): void
    {
        isCan('h');
        if ($tipe == 2) {
            SentItem::destroy($this->request['id_cb'] ?? $id);
        } elseif ($tipe == 1) {
            Inbox::destroy($this->request['id_cb'] ?? $id);
        } else {
            Outbox::destroy($this->request['id_cb'] ?? $id);
        }

            if ($tipe == 1) {
                redirect_with('success', 'Data berhasil dihapus', ci_route('sms'));
            } elseif ($tipe == 2) {
                redirect_with('success', 'Data berhasil dihapus', ci_route('sms.sentitem'));
            } elseif ($tipe == 3) {
                redirect_with('success', 'Data berhasil dihapus', ci_route('sms.pending'));
            } else {
                redirect_with('success', 'Data berhasil dihapus', ci_route('sms.outbox'));
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
            'subjek'     => htmlentities((string) $request['subjek']),
            'isi'        => htmlentities((string) $request['isi']),
            'created_by' => ci_auth()->id,
            'updated_by' => ci_auth()->id,
        ];
    }

    protected function kirimPesanGrup($data = [])
    {
        $result        = [];
        $daftarAnggota = AnggotaGrup::where('id_grup', bilangan($data['id_grup']))->dataAnggota()->get();

        foreach ($daftarAnggota as $anggota) {
            // Kirim pesan berdasarkan pilihan hubung warga
            // Prioritas : berdasarkan pilihan, telegram jika tidak tersedia, jangan kirim
            switch (true) {
                case (bool) setting('aktifkan_sms') && $anggota->hubung_warga = 'SMS' && null !== $anggota->telepon:
                    $kirim                                                    = Outbox::create([
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
                        $kirim = $this->otp->driver('email')->kirimPesan([
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
                        $kirim = $this->otp->driver('telegram')->kirimPesan([
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
