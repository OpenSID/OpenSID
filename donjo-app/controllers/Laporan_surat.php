<?php if ( !defined( 'BASEPATH' ) ) {
    exit( 'No direct script access allowed' );
}

/*
 *  File ini:
 *
 * Controller untuk modul surat
 *
 * donjo-app/controllers/Laporan_surat.php
 *
 */
/*
 *  File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package    OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright    Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright    Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link     https://github.com/OpenSID/OpenSID
 */

class Laporan_surat extends Admin_Controller
{

    public $modul_ini = 3;
    public $sub_modul_ini = 316;

    public function __construct()
    {
        parent::__construct();
        $this->load->model( ['config_model', 'surat_model', 'pamong_model'] );

    }

    public function index( $tahun = null, $bulan = null )
    {
        if ( !$tahun && isset( $_GET['tahun'] ) ) {
            $tahun = $_GET['tahun'];
        }

        if ( !$bulan && isset( $_GET['bulan'] ) ) {
            $bulan = $_GET['bulan'];
        }

        if ( !is_numeric( $tahun ) || $tahun < 2000 || $tahun > date( 'Y' ) ) {
            $tahun = date( 'Y' );
        }

        if ( !is_numeric( $bulan ) || $bulan <= 0 || $bulan > 12 ) {
            $bulan = date( 'n' );
        }

        $config = $this->config_model->get_data();

        $result = $this->list_data( $tahun, $bulan );

        $this->set_minsidebar( 1 );
        $this->render( 'laporan/surat/list', compact( 'config', 'tahun', 'bulan', 'result' ) );
    }

    public function detail( $tahun = '', $bulan = '', $id_jenis = '' )
    {
        if ( !( is_numeric( $tahun ) && is_numeric( $bulan ) && is_numeric( $id_jenis ) ) ) {
            return;
        }
        $surat = $this->db->where( 'id', $id_jenis )->get( 'tweb_surat_format' )->row_array();
        if ( !$surat ) {
            return;
        }

        $query = "SELECT u.*, n.nama AS nama, w.nama AS nama_user, n.nik AS nik,
            k.nama AS format, k.url_surat as berkas, k.kode_surat as kode_surat,
            s.id_pend as pamong_id_pend, s.pamong_nama AS pamong, p.nama as nama_pamong_desa
            FROM log_surat u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			LEFT JOIN tweb_surat_format k ON u.id_format_surat = k.id
			LEFT JOIN tweb_desa_pamong s ON u.id_pamong = s.pamong_id
			LEFT JOIN tweb_penduduk p ON s.id_pend = p.id
			LEFT JOIN user w ON u.id_user = w.id
            WHERE YEAR(u.tanggal) = ? AND month(u.tanggal) = ? AND k.id = ?
            ORDER BY u.tanggal";

        $result = $this->db->query( $query, [$tahun, $bulan, $id_jenis] )->result_array();
        foreach ( $result as &$row ) {

            $row['url_surat'] = is_file( FCPATH . LOKASI_ARSIP . @$row['nama_surat'] ) ?
            base_url( LOKASI_ARSIP . $row['nama_surat'] ) : false;

            $row['url_lampiran'] = is_file( FCPATH . LOKASI_ARSIP . $row['lampiran'] ) ?
            base_url( LOKASI_ARSIP . $row['lampiran'] ) : false;
        }

        unset( $row );

        $this->load->view( 'laporan/surat/detail', compact( 'result', 'tahun', 'bulan', 'surat' ) );
    }

    public function dialog( $aksi = '', $tahun = '', $bulan = '' )
    {
        if ( !( in_array( $aksi, ['cetak', 'unduh'] ) && is_numeric( $tahun ) && is_numeric( $bulan ) ) ) {
            return;
        }

        $pamong = $this->pamong_model->list_data();

        $this->load->view( 'laporan/dialog', compact( 'aksi', 'tahun', 'bulan', 'pamong' ) );
    }

    public function unduh( $pamong_id = null )
    {
        if (  ( $data = $this->prepare_data() ) === false ) {
            return;
        }
        $nama_file = "Laporan Surat Bulan " . opensid_nama_bulan( $data['bulan'] ) . ".xls";
        header( "Content-type: application/octet-stream" );
        header( "Content-Disposition: attachment; filename=\"$nama_file\"" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        $this->load->view( 'laporan/surat/cetak', $data );
    }

    public function cetak()
    {
        if (  ( $data = $this->prepare_data() ) === false ) {
            return;
        }
        $this->load->view( 'laporan/surat/cetak', $data );

    }
    private function prepare_data()
    {

        $tahun = intval( $this->input->get( 'tahun' ) );
        $bulan = intval( $this->input->get( 'bulan' ) );
        $pamong_ttd = intval( $this->input->get( 'pamong_ttd' ) );
        if ( !( $tahun && $bulan && $pamong_ttd ) ) {
            return false;
        }
        $result = $this->list_data( $tahun, $bulan );
        $pamong = $this->pamong_model->get_data( $pamong_ttd );
        $config = $this->config_model->get_data();
        return compact( 'tahun', 'bulan', 'result', 'pamong', 'config' );
    }

    private function list_data( $tahun, $bulan )
    {
        $query = "select f.id as id_jenis, f.nama as jenis, COUNT(*) as jumlah
        FROM log_surat s INNER join tweb_surat_format f on f.id = s.id_format_surat
        WHERE  YEAR(s.tanggal) = ? AND month(s.tanggal) = ?
        GROUP by s.id_format_surat order by f.nama";
        return $this->db->query( $query, [$tahun, $bulan] )->result_array();
    }

}
