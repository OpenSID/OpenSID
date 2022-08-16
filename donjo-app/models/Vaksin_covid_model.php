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

class Vaksin_covid_model extends MY_Model
{
    protected $tabel_penduduk = 'penduduk_hidup';
    protected $table_vaksin   = 'covid19_vaksin';
    protected $penduduk_key   = 'id';
    protected $vaksin_key     = 'id_penduduk';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('referensi_model');
    }

    public function jenis_vaksin()
    {
        // Data awal
        $awal = $this->referensi_model->list_ref(JENIS_VAKSIN);

        // Dari database
        $data = $this->db
            ->get($this->table_vaksin)
            ->result_array();

        $jenis_vaksin_1 = array_column($data, 'jenis_vaksin_1');
        $jenis_vaksin_2 = array_column($data, 'jenis_vaksin_2');
        $jenis_vaksin_3 = array_column($data, 'jenis_vaksin_3');
        $jenis_vaksin   = array_unique(array_merge($awal, $jenis_vaksin_1, $jenis_vaksin_2, $jenis_vaksin_3));

        return array_values(array_filter($jenis_vaksin));
    }

    public function dusun_sql()
    {
        $kf = $this->session->dusun;
        if (isset($kf)) {
            $this->db->where("((p.id_kk <> '0' AND cp.dusun = '{$kf}') OR (p.id_kk = '0' AND ck.dusun = '{$kf}'))");
        }
    }

    public function vaksin_sql()
    {
        $kf = $this->session->vaksin;

        if (isset($kf)) {
            if ($kf == '4') {
                $this->db->group_start();
                $this->db->group_start();
                $this->db->where('vaksin_1', 0);
                $this->db->where('vaksin_2', 0);
                $this->db->where('vaksin_3', 0);
                $this->db->where('tunda', 0);
                $this->db->group_end();
                $this->db->or_where('vaksin_1');
                $this->db->group_end();
            } elseif ($kf == '5') {
                $this->db->where('tunda', '1');
            } else {
                $this->db->where("vaksin_{$kf}", '1');
            }
        }
    }

    public function jenis_vaksin_sql()
    {
        $kf = $this->session->jenis_vaksin;

        if (isset($kf)) {
            $this->db->group_start();
            $this->db->where('jenis_vaksin_1', $kf);
            $this->db->or_where('jenis_vaksin_2', $kf);
            $this->db->or_where('jenis_vaksin_3', $kf);
            $this->db->group_end();
        }
    }

    public function tanggal_vaksin_sql()
    {
        $kf = $this->session->tanggal_vaksin;

        if (isset($kf)) {
            $kf = rev_tgl($kf);
            $this->db->group_start();
            $this->db->where('tgl_vaksin_1', $kf);
            $this->db->or_where('tgl_vaksin_2', $kf);
            $this->db->or_where('tgl_vaksin_3', $kf);
            $this->db->group_end();
        }
    }

    public function umur_sql($umur)
    {
        $umur = explode('-', $umur);
        $this->db->where("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(p.tanggallahir)),'%Y') + 0) >= " . (int) $umur[0]);
        if (isset($umur[1]) && $umur[1] > $umur[0]) {
            $this->db->where("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(p.tanggallahir)),'%Y') + 0) <=  " . (int) $umur[1]);
        }
    }

    public function cari($value = '')
    {
        $kf = $this->session->cari;
        if (isset($kf)) {
            $this->db
                ->group_start()
                ->like('p.nik', $kf)
                ->or_like('p.nama', $kf)
                ->group_end();
        }
    }

    public function count_reg()
    {
        $this->db->select("count({$this->penduduk_key}) AS count")
            ->from("{$this->tabel_penduduk} as p")
            ->join("{$this->table_vaksin} as v", "p.{$this->penduduk_key} = v.{$this->vaksin_key}", 'left')
            ->join('tweb_keluarga AS kk', 'p.id = kk.id')
            ->join('tweb_wil_clusterdesa AS w', 'kk.id_cluster = w.id');

        return $this->db->get()->row();
    }

    public function penduduk_sql()
    {
        $sebutan_dusun = ucwords($this->setting->sebutan_dusun);
        $this->db->select('p.*, v.*, kk.no_kk, ck.rt, ck.rw, ck.dusun, s.nama as jenis_kelamin ')
            ->select("(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.tanggallahir)), '%Y')+0) AS umur")
            ->select("(
                case when (p.id_kk IS NULL or p.id_kk = 0)
                    then
                        case when (cp.dusun = '-' or cp.dusun = '')
                            then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
                            else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutan_dusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
                        end
                    else
                        case when (ck.dusun = '-' or ck.dusun = '')
                            then CONCAT(COALESCE(kk.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
                            else CONCAT(COALESCE(kk.alamat, ''), ' {$sebutan_dusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
                        end
                end) AS alamat")
            ->join("{$this->table_vaksin} as v", "p.{$this->penduduk_key} = v.{$this->vaksin_key}", 'left')
            ->join('tweb_wil_clusterdesa cp', 'p.id_cluster = cp.id', 'left')
            ->join('tweb_keluarga AS kk', 'p.id_kk = kk.id', 'left')
            ->join('tweb_wil_clusterdesa ck', 'kk.id_cluster = ck.id', 'left')
            ->join('tweb_penduduk_sex AS s', 'p.sex = s.id', 'left');
        $this->dusun_sql();
        $this->vaksin_sql();
        $this->tanggal_vaksin_sql();
        $this->jenis_vaksin_sql();
        if (isset($this->session->umur)) {
            $this->umur_sql($this->session->umur);
        }
        $this->cari();
    }

    public function list_penduduk($page = 1)
    {
        $this->penduduk_sql();
        $per_page = ($page == 0) ? null : $this->session->per_page;

        return $this->db->get("{$this->tabel_penduduk} as p", $per_page, ($page - 1) * $per_page)->result();
    }

    public function paging($p = 0)
    {
        $this->penduduk_sql();
        $jml_data = $this->db->get("{$this->tabel_penduduk} as p")->num_rows();
        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function data_penduduk($id = null)
    {
        if ($id == null) {
            return false;
        }
        $this->penduduk_sql();
        $this->db->where('p.id', $id);

        return $this->db->get("{$this->tabel_penduduk} as p")->row();
    }

    public function update_vaksin()
    {
        unset($this->session->validation_error, $this->session->success);
        $data           = $this->input->post();
        $error_validasi = $this->validasi_data($data);

        if (! empty($error_validasi)) {
            foreach ($error_validasi as $error) {
                $this->session->error_msg .= ': ' . $error . '\n';
            }
            $this->session->post    = $this->input->post();
            $this->session->success = -1;

            return;
        }

        if ($data['tunda'] == 0) {
            $this->upload_sertifikat($data);
        } else {
            $this->upload_surat($data);
        }

        $update = [
            'id_penduduk'      => $data['id_penduduk'],
            'vaksin_1'         => $data['vaksin_1'],
            'tgl_vaksin_1'     => $data['tgl_vaksin_1'],
            'dokumen_vaksin_1' => $data['dokumen_vaksin_1'] ?? null,
            'jenis_vaksin_1'   => $data['jenis_vaksin_1'] ?? null,
            'vaksin_2'         => $data['vaksin_2'],
            'tgl_vaksin_2'     => $data['tgl_vaksin_2'],
            'dokumen_vaksin_2' => $data['dokumen_vaksin_2'] ?? null,
            'jenis_vaksin_2'   => $data['jenis_vaksin_2'] ?? null,
            'vaksin_3'         => $data['vaksin_3'],
            'tgl_vaksin_3'     => $data['tgl_vaksin_3'] ?? null,
            'dokumen_vaksin_3' => $data['dokumen_vaksin_3'] ?? null,
            'jenis_vaksin_3'   => $data['jenis_vaksin_3'] ?? null,
            'tunda'            => $data['tunda'],
            'keterangan'       => $data['keterangan'] ?? null,
            'surat_dokter'     => $data['surat_dokter'] ?? null,
        ];
        $hasil = $this->db->replace($this->table_vaksin, $update);

        status_sukses($hasil);
    }

    public function upload_sertifikat(&$data)
    {
        $this->load->library('upload');

        for ($i = 1; $i <= 3; $i++) {
            $file = "vaksin_{$i}";
            if ($_FILES[$file]['size'] != 0 && $data["tgl_vaksin_{$i}"]) {
                $data["dokumen_vaksin_{$i}"] = $this->do_upload($file, $data);
            }
        }
    }

    public function do_upload($file, $data)
    {
        $config['upload_path']   = LOKASI_VAKSIN;
        $config['file_name']     = 'vaksin';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 1024;
        $config['overwrite']     = true;
        $this->upload->initialize($config);

        try {
            $upload = $this->upload->do_upload($file);

            if (! $upload) {
                $this->session->error_msg = $this->upload->display_errors();
                $this->session->success   = -1;

                return redirect('vaksin_covid/form?terdata=' . $data['id_penduduk']);
            }

            $uploadData = $this->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $config['upload_path'] . $uploadData['file_name'],
                $config['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];

            return $uploadData['file_name'];
        } catch (Exception $e) {
            $this->session->post    = $this->input->post();
            $this->session->success = -1;

            return redirect('vaksin_covid/form?terdata=' . $data['id_penduduk']);
        }
    }

    public function upload_surat(&$data)
    {
        $this->load->library('upload');
        if ($_FILES['surat_dokter']['size'] != 0 && $data['tunda'] == 1) {
            $file                 = 'surat_dokter';
            $data['surat_dokter'] = $this->do_upload($file, $data);
        }
    }

    public function validasi_data(&$data)
    {
        if ((int) ($data['id_penduduk']) == 0) {
            $valid[] = 'NIK belum di pilih';
        }

        //  steril data
        $data['id_penduduk']    = (int) ($data['id_penduduk']);
        $data['vaksin_1']       = (int) ($data['vaksin_1']);
        $data['tgl_vaksin_1']   = (! isset($data['tgl_vaksin_1']) || $data['tgl_vaksin_1'] == '') ? null : rev_tgl($data['tgl_vaksin_1']);
        $data['jenis_vaksin_1'] = (isset($data['jenis_vaksin_1']) || $data['jenis_vaksin_1'] != '') ? alfanumerik_spasi($data['jenis_vaksin_1']) : null;
        $data['vaksin_2']       = (int) ($data['vaksin_2']);
        $data['tgl_vaksin_2']   = (! isset($data['tgl_vaksin_2']) || $data['tgl_vaksin_2'] == '') ? null : rev_tgl($data['tgl_vaksin_2']);
        $data['jenis_vaksin_2'] = (isset($data['jenis_vaksin_2']) || $data['jenis_vaksin_2'] != '') ? alfanumerik_spasi($data['jenis_vaksin_2']) : null;
        $data['vaksin_3']       = (int) ($data['vaksin_3']);
        $data['tgl_vaksin_3']   = (! isset($data['tgl_vaksin_3']) || $data['tgl_vaksin_3'] == '') ? null : rev_tgl($data['tgl_vaksin_3']);
        $data['jenis_vaksin_3'] = (isset($data['jenis_vaksin_3']) || $data['jenis_vaksin_3'] != '') ? alfanumerik_spasi($data['jenis_vaksin_3']) : null;
        $data['tunda']          = (int) ($data['tunda']);
        $data['surat_dokter']   = $data['surat_dokter'] ?? null;
        $data['keterangan']     = alfanumerik_spasi($data['keterangan']);

        if (! empty($valid)) {
            $this->session->success = -1;
        }

        return $valid;
    }

    public function rekap($umur)
    {
        $this->penduduk_sql();

        if ($umur != 0) {
            $this->umur_sql($umur);
        }

        return $this->db->get("{$this->tabel_penduduk} as p")->result();
    }
}
