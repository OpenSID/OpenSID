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

use App\Models\FormatSurat;

class Surat_master_model extends MY_Model
{
    protected $table = 'tweb_surat_format';

    private function validasi_surat(&$data): void
    {
        $data['nama'] = nama_terbatas($data['nama']);
    }

    public function update($id = 0)
    {
        $data = $this->input->post();
        unset($data['id_surat']);
        $this->validasi_surat($data);

        $outp = $this->config_id()
            ->where('id', $id)
            ->update($this->table, $data);

        status_sukses($outp); //Tampilkan Pesan

        return $id;
    }

    public function delete($id = null)
    {
        // Ambil data surat sebelum dihapus
        $before = FormatSurat::findOrFail($id);

        if (in_array($before->jenis, [1, 3])) {
            redirect_with('error', 'Gagal Hapus Data, Surat Bawaan Sistem Tidak Dapat Dihapus');
        }

        $outp = $before->delete();

        if ($outp) {
            //hapus file dan folder penyimpanan template surat
            delete_files($before->lokasi_surat, true, false, 1);

            return true;
        }

        return false;
    }

    public function deleteAll()
    {
        $outp = true;

        foreach ($this->input->post('id_cb') as $id) {
            $outp = $outp && $this->delete($id);
        }

        return $outp;
    }
}
