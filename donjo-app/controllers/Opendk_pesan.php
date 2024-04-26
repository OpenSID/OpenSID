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

use App\Models\Pesan;
use GuzzleHttp\Exception\ClientException;

defined('BASEPATH') || exit('No direct script access allowed');

class Opendk_pesan extends Admin_Controller
{
    public $modul_ini        = 'opendk';
    public $sub_modul_ini    = 'pesan';
    protected $_list_session = ['cari', 'status'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function cek()
    {
        // cek setting server ke opendk
        if (empty($this->setting->sinkronisasi_opendk)) {
            $message = "Pengaturan sinkronisasi masih kosong. Periksa Pengaturan Sinkronisasi di <a href='" . ci_route('sinkronisasi') . '#tab_buat_key' . "' style='text-decoration:none;'' ><strong>Sinkronisasi&nbsp;(<i class='fa fa-gear'></i>)</strong></a>";

            return view('admin.opendkpesan.error', ['message' => $message]);
        }

        return true;
    }

    public function index()
    {
        if (! $this->cek()) {
            return;
        }

        get_pesan_opendk();
        $selected_nav = 'pesan';
        $status       = $this->session->status;
        $pesan        = Pesan::orderBy('sudah_dibaca', 'ASC')
            ->orderBy('created_at', 'DESC');
        $cari = null;

        if ($this->session->status != null) {
            $pesan->where('sudah_dibaca', '=', $this->session->status);
        }

        if ($this->session->cari) {
            $cari = $this->session->cari;
            $pesan->whereHas('detailPesan', static function ($q) use ($cari): void {
                $q->where('text', 'LIKE', "%{$cari}%");
            });
            $pesan->orWhere('judul', 'LIKE', "%{$cari}%");
            $pesan->with('detailPesan', static function ($q) use ($cari): void {
                $q->where('text', 'LIKE', "%{$cari}%");
            });
        } else {
            $pesan->with(['detailPesan']);
        }

        $pesan->where('diarsipkan', '=', 0);
        $pesan = $pesan->paginate(25);

        return view('admin.opendkpesan.index', ['pesan' => $pesan, 'selected_nav' => $selected_nav, 'status' => $status, 'cari' => $cari]);
    }

    public function clear($return = ''): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = 50;
        redirect($this->controller . "/{$return}");
    }

    public function filter($filter, $return = ''): void
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect($this->controller . "/{$return}");
    }

    public function search($slash = ''): void
    {
        $cari  = alfanumerik_spasi($this->request['cari']);
        $slash = alfanumerik_spasi($slash);

        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
        redirect($this->controller . "/{$slash}");
    }

    public function show($id)
    {
        $pesan = Pesan::with(['detailPesan'])
            ->where('id', '=', $id)
            ->first();

        $form_action = ci_route('opendk_pesan.insert.' . $id);
        Pesan::where('id', '=', $id)
            ->update([
                'sudah_dibaca' => 1,
            ]);

        return view('admin.opendkpesan.show', ['pesan' => $pesan, 'form_action' => $form_action]);
    }

    public function form()
    {
        isCan('u');
        $form_action = ci_route('opendk_pesan.insert');
        $action      = 'Tambah';

        return view('admin.opendkpesan.form', ['action' => $action, 'form_action' => $form_action]);
    }

    public function insert($id = null): void
    {
        isCan('u');
        $request = static::validate($this->request);

        try {
            $config = $this->header['desa'];

            //cek id pesan
            if ($id == null) {
                $params = [
                    'kode_desa'     => kode_wilayah($this->header['desa']['kode_desa']),
                    'pesan'         => $request['pesan'],
                    'judul'         => $request['judul'],
                    'pengirim'      => 'desa',
                    'nama_pengirim' => $this->setting->sebutan_desa . ' ' . $config['nama_desa'] . ' - ' . $this->session->nama,
                ];
            } else {
                $params = [
                    'pesan_id'      => $id,
                    'pesan'         => $request['pesan'],
                    'kode_desa'     => kode_wilayah($this->header['desa']['kode_desa']),
                    'pengirim'      => 'desa',
                    'nama_pengirim' => $this->setting->sebutan_desa . ' ' . $config['nama_desa'] . ' - ' . $this->session->nama,
                ];
            }

            $client   = new GuzzleHttp\Client();
            $response = $client->post("{$this->setting->api_opendk_server}/api/v1/pesan", [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization'    => "Bearer {$this->setting->api_opendk_key}",
                ],
                'form_params' => $params,
            ])->getBody()->getContents();
            $data_respon = json_decode($response, null);

            if ($data_respon->status == false) {
                redirect_with('error', $data_respon->message);
            } else {
                redirect_with('success', 'pesan berhasil terkirim');
            }
        } catch (ClientException $cx) {
            log_message('error', $cx);
            redirect_with('error', 'error : ClientException');
        }
    }

    public function arsip()
    {
        $selected_nav = 'arsip';
        $pesan        = Pesan::where('diarsipkan', '=', '1')->with(['detailPesan'])->paginate(25);

        return view('admin.opendkpesan.index', ['pesan' => $pesan, 'selected_nav' => $selected_nav]);
    }

    public function arsipkan(): void
    {
        isCan('h');

        $array = json_decode($this->request['array_id'], null);

        Pesan::whereIn('id', $array)->update([
            'diarsipkan' => 1,
        ]);
        redirect_with('success', 'pesan berhasil diarsipkan');
    }

    // Hanya filter inputan
    protected static function validate($request = []): array
    {
        return [
            'judul' => alfanumerik_spasi($request['judul']),
            'pesan' => $request['pesan'],
        ];
    }

    public function getPesan()
    {
        try {
            $response = $this->client->post("{$this->setting->api_opendk_server}/api/v1/pesan", [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization'    => "Bearer {$this->setting->api_opendk_key}",
                ],
                'form_params' => [
                    'kode_desa' => kode_wilayah($this->header['desa']['kode_desa']),

                ],
            ])
                ->getBody();
        } catch (ClientException $cx) {
            log_message('error', $cx);
        }

        return $response ?? null;
    }
}
