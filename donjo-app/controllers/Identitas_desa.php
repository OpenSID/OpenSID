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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Config;
use App\Models\Pamong;
use App\Models\Wilayah;

class Identitas_desa extends Admin_Controller
{
    private $viewPath = 'admin.identitas_desa';

    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 200;
        $this->sub_modul_ini = 17;
    }

    /**
     * View index identitas desa
     *
     * @return void
     */
    public function index()
    {
        return view("{$this->viewPath}.index", [
            'main' => Config::with('pamong.penduduk')->first(),
        ]);
    }

    /**
     * View form ubah identitas desa
     *
     * @return void
     */
    public function form()
    {
        $this->redirect_hak_akses('u');

        $main   = Config::with('pamong')->first();
        $pamong = Pamong::with('penduduk')->status()->get();

        if ($main) {
            $form_action = route('identitas_desa.update', $main->id);
        } else {
            $form_action = route('identitas_desa.insert');
        }

        return view("{$this->viewPath}.form", compact('main', 'pamong', 'form_action'));
    }

    /**
     * Proses tambah identitas desa
     *
     * @return void
     */
    public function insert()
    {
        $this->redirect_hak_akses('u');

        if (Config::insert($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    /**
     * Proses ubah identitas desa
     *
     * @param int $id
     *
     * @return void
     */
    public function update($id = 0)
    {
        $this->redirect_hak_akses('u');

        $data    = Config::find($id) ?? show_404();
        $id_lama = $data['pamong_id'];

        if ($data->update(static::validate($this->request))) {
            static::pamong($id_lama, $this->request['pamong_id']);
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    /**
     * View Form Ubah Peta
     *
     * @param string $tipe
     *
     * @return void
     */
    public function maps($tipe = 'kantor')
    {
        $data_desa            = Config::first();
        $data['desa']         = $data_desa;
        $data['poly']         = ($tipe == 'wilayah') ? 'multi' : 'poly';
        $data['wil_ini']      = $data_desa;
        $data['wil_atas']     = $data_desa;
        $data['dusun_gis']    = Wilayah::dusun()->get();
        $data['rw_gis']       = Wilayah::rw()->get();
        $data['rt_gis']       = Wilayah::rt()->get();
        $data['nama_wilayah'] = ucwords($this->setting->sebutan_desa . ' ' . $data_desa->nama_desa);
        $data['wilayah']      = ucwords($this->setting->sebutan_desa . ' ' . $data_desa->nama_desa);
        $data['breadcrumb']   = [
            ['link' => route('identitas_desa'), 'judul' => 'Identitas ' . ucwords($this->setting->sebutan_desa)],
        ];

        $data['form_action'] = route('identitas_desa.update_maps', $tipe);

        $this->render('sid/wilayah/maps_' . $tipe, $data);
    }

    /**
     * Proses ubah peta
     *
     * @param string $tipe
     *
     * @return void
     */
    public function update_maps($tipe = 'kantor')
    {
        $this->redirect_hak_akses('u');

        $data       = Config::first();
        $data->zoom = bilangan($this->request['zoom']);

        if ($tipe == 'kantor') {
            $data->lat = koordinat($this->request['lat']);
            $data->lng = koordinat($this->request['lng']);
        } else {
            $data->path  = htmlentities($this->request['path']);
            $data->warna = warna($this->request['warna']);
        }

        if ($data->save()) {
            redirect_with('success', 'Berhasil Ubah Peta ' . ucwords($tipe));
        }
        redirect_with('error', 'Gagal Ubah Peta ' . ucwords($tipe));
    }

    /**
     * Proses kosongkan path peta
     *
     * @param string $id
     *
     * @return void
     */
    public function kosongkan()
    {
        $this->redirect_hak_akses('u');

        $data       = Config::first();
        $data->path = null;

        if ($data->save()) {
            redirect_with('success', 'Berhasil Kosongkan Peta');
        }
        redirect_with('error', 'Gagal Kosongkan Peta');
    }

    // Hanya filter inputan
    protected static function validate($request = [])
    {
        return [
            'logo'              => static::unggah('logo', true) ?? $request['old_logo'],
            'kantor_desa'       => static::unggah('kantor_desa') ?? $request['old_kantor_desa'],
            'nama_desa'         => nama_terbatas($request['nama_desa']),
            'kode_desa'         => bilangan($request['kode_desa']),
            'kode_pos'          => bilangan($request['kode_pos']),
            'pamong_id'         => bilangan($request['pamong_id']),
            'alamat_kantor'     => alamat($request['alamat_kantor']),
            'email_desa'        => email($request['email_desa']),
            'telepon'           => bilangan($request['telepon']),
            'website'           => alamat_web($request['website']),
            'nama_kecamatan'    => nama_terbatas($request['nama_kecamatan']),
            'kode_kecamatan'    => bilangan($request['kode_kecamatan']),
            'nama_kepala_camat' => nama($request['nama_kepala_camat']),
            'nip_kepala_camat'  => nomor_surat_keputusan($request['nip_kepala_camat']),
            'nama_kabupaten'    => nama($request['nama_kabupaten']),
            'kode_kabupaten'    => bilangan($request['kode_kabupaten']),
            'nama_propinsi'     => nama_terbatas($request['nama_propinsi']),
            'kode_propinsi'     => bilangan($request['kode_propinsi']),
        ];
    }

    // TODO : Ganti cara ini
    protected static function pamong($id_lama = '', $id_baru = '')
    {
        Pamong::where('pamong_id', $id_lama)
            ->update([
                'jabatan'       => null,
                'pamong_status' => 0,
                'pamong_ttd'    => 0,
            ]);

        Pamong::where('pamong_id', $id_baru)
            ->update([
                'jabatan'       => ucwords(get_instance()->setting->sebutan_kepala_desa),
                'pamong_status' => 1,
                'pamong_ttd'    => 1,
            ]);
    }

    // TODO : Ganti cara ini
    protected static function unggah($jenis = '', $resize = false)
    {
        $CI = &get_instance();
        $CI->load->library('upload');
        $CI->uploadConfig = [
            'upload_path'   => LOKASI_LOGO_DESA,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];
        // Adakah berkas yang disertakan?
        if (empty($_FILES[$jenis]['name'])) {
            return null;
        }
        // Tes tidak berisi script PHP
        if (isPHP($_FILES[$jenis]['tmp_name'], $_FILES[$jenis]['name'])) {
            redirect_with('error', 'Jenis file ini tidak diperbolehkan');
        }

        $uploadData = null;
        // Inisialisasi library 'upload'
        $CI->upload->initialize($CI->uploadConfig);
        // Upload sukses
        if ($CI->upload->do_upload($jenis)) {
            $uploadData = $CI->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $CI->uploadConfig['upload_path'] . $uploadData['file_name'],
                $CI->uploadConfig['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
        } else {
            redirect_with('error', $CI->upload->display_errors(null, null));
        }

        if (! empty($uploadData)) {
            if ($resize) {
                $tipe_file = TipeFile($_FILES['logo']);
                $dimensi   = ['width' => 100, 'height' => 100];
                resizeImage(LOKASI_LOGO_DESA . $uploadData['file_name'], $tipe_file, $dimensi);
                resizeImage(LOKASI_LOGO_DESA . $uploadData['file_name'], $tipe_file, ['width' => 16, 'height' => 16], LOKASI_LOGO_DESA . 'favicon.ico');
            }

            return $uploadData['file_name'];
        }

        return null;
    }
}
