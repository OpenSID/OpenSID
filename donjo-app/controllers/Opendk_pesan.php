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

use App\Models\Pesan;
use GuzzleHttp\Exception\ClientException;

defined('BASEPATH') || exit('No direct script access allowed');

class Opendk_pesan extends Admin_Controller
{
    public $modul_ini     = 'opendk';
    public $sub_modul_ini = 'pesan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function cek()
    {
        // cek setting server ke opendk
        if (empty(setting('sinkronisasi_opendk'))) {
            $message = "Pengaturan sinkronisasi masih kosong. Periksa Pengaturan Sinkronisasi di <a href='" . ci_route('sinkronisasi') . '#tab_buat_key' . "' style='text-decoration:none;'' ><strong>Sinkronisasi&nbsp;(<i class='fa fa-gear'></i>)</strong></a>";

            return view('admin.opendkpesan.error', ['message' => $message]);
        }

        return true;
    }

    public function index()
    {
        if (! $this->cek()) {
            return null;
        }

        get_pesan_opendk();
        $selected_nav = 'pesan';

        return view('admin.opendkpesan.index', ['selected_nav' => $selected_nav]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status') ?? null;
            $arsip  = $this->input->get('arsip') ?? 0;
            $pesan  = Pesan::with(['detailPesan'])->status($status)->where('diarsipkan', '=', $arsip)->orderBy('sudah_dibaca', 'ASC')
                ->orderBy('created_at', 'DESC');

            return datatables()->of($pesan)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('opendk_pesan.show', $row->id) . '" class="btn bg-blue btn-sm"  title="Tampilkan Pesan"><i class="fa fa-eye"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('DT_RowAttr', static function ($row): array {
                    $style = '';

                    if ($row->sudah_dibaca == 0) {
                        $style = 'info';
                    }

                    return ['class' => $style];
                })
                ->editColumn('judul', static fn ($row): string => $row->judul . ' - ' . strip_tags($row->detailpesan[0]->text) ?? '')
                ->editColumn('tipe', static fn ($row): string => $row->jenis == 'Pesan Masuk' ? 'Pesan Keluar' : 'Pesan Masuk')
                ->editColumn('status', static function ($row): string {
                    if ($row->sudah_dibaca == 0) {
                        return '<span class="label label-warning">Belum dibaca</span>';
                    }

                    return '<span class="label label-success">Sudah dibaca</span>';
                })
                ->rawColumns(['aksi', 'status'])
                ->make();
        }

        return show_404();
    }

    public function clear($return = ''): void
    {
        redirect($this->controller . "/{$return}");
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
                    'nama_pengirim' => setting('sebutan_desa') . ' ' . $config['nama_desa'] . ' - ' . $this->session->nama,
                ];
            } else {
                $params = [
                    'pesan_id'      => $id,
                    'pesan'         => $request['pesan'],
                    'kode_desa'     => kode_wilayah($this->header['desa']['kode_desa']),
                    'pengirim'      => 'desa',
                    'nama_pengirim' => setting('sebutan_desa') . ' ' . $config['nama_desa'] . ' - ' . $this->session->nama,
                ];
            }

            $client   = new GuzzleHttp\Client();
            $response = $client->post(setting('api_opendk_server') . '/api/v1/pesan', [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization'    => 'Bearer ' . setting('api_opendk_key'),
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

        return view('admin.opendkpesan.index', ['selected_nav' => $selected_nav]);
    }

    public function arsipkan(): void
    {
        isCan('h');

        $array = json_decode((string) $this->request['array_id'], null);

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
            $response = $this->client->post(setting('api_opendk_server') . '/api/v1/pesan', [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization'    => 'Bearer ' . setting('api_opendk_key'),
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
