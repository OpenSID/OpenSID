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
require_once './vendor/codeigniter/framework/system/libraries/Upload.php'; // This is not auto loaded

class MY_Upload extends CI_Upload
{
    /**
     * Cek script untuk kode berbahaya
     *
     * @var bool
     */
    public $cek_script = true;

    /**
     * Penambahan timestamp pada nama file
     *
     * @var bool
     */
    public $timestamp = true;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    // --------------------------------------------------------------------

    /**
     * Perform the file upload
     * Dengan tambahan pemeriksaan kode berbaya
     *
     * @param string $field
     *
     * @return bool
     */
    public function do_upload($field = 'userfile')
    {
        // Is $_FILES[$field] set? If not, no reason to continue.
        if (isset($_FILES[$field])) {
            $_file = $_FILES[$field];
        }
        // Does the field name contain array notation?
        elseif (($c = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $field, $matches)) > 1) {
            $_file = $_FILES;

            for ($i = 0; $i < $c; $i++) {
                // We can't track numeric iterations, only full field names are accepted
                if (($field = trim($matches[0][$i], '[]')) === '' || ! isset($_file[$field])) {
                    $_file = null;
                    break;
                }

                $_file = $_file[$field];
            }
        }

        if (! isset($_file)) {
            $this->set_error('upload_no_file_selected', 'debug');

            return false;
        }

        // Is the upload path valid?
        if (! $this->validate_upload_path()) {
            // errors will already be set by validate_upload_path() so just return FALSE
            return false;
        }

        // Was the file able to be uploaded? If not, determine the reason why.
        if (! is_uploaded_file($_file['tmp_name'])) {
            $error = $_file['error'] ?? 4;

            switch ($error) {
                case UPLOAD_ERR_INI_SIZE:
                    $this->set_error('upload_file_exceeds_limit', 'info');
                    break;

                case UPLOAD_ERR_FORM_SIZE:
                    $this->set_error('upload_file_exceeds_form_limit', 'info');
                    break;

                case UPLOAD_ERR_PARTIAL:
                    $this->set_error('upload_file_partial', 'debug');
                    break;

                case UPLOAD_ERR_NO_FILE:
                default:
                    $this->set_error('upload_no_file_selected', 'debug');
                    break;

                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->set_error('upload_no_temp_directory', 'error');
                    break;

                case UPLOAD_ERR_CANT_WRITE:
                    $this->set_error('upload_unable_to_write_file', 'error');
                    break;

                case UPLOAD_ERR_EXTENSION:
                    $this->set_error('upload_stopped_by_extension', 'debug');
                    break;
            }

            return false;
        }

        // Set the uploaded data as class variables
        $this->file_temp = $_file['tmp_name'];
        $this->file_size = $_file['size'];

        // Skip MIME type detection?
        if ($this->detect_mime !== false) {
            $this->_file_mime_type($_file);
        }

        $this->file_type = preg_replace('/^(.+?);.*$/', '\\1', $this->file_type);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->file_name = $this->_prep_filename($_file['name']);

        if ($this->timestamp) {
            $this->file_name = time() . '_' . $this->file_name;
        }

        $this->file_ext    = $this->get_extension($this->file_name);
        $this->client_name = $this->file_name;

        // Is the file type allowed to be uploaded?
        if (! $this->is_allowed_filetype()) {
            $this->set_error('upload_invalid_filetype', 'debug');

            return false;
        }

        // cek malicious code dalam gambar
        if ($this->cek_script && isPHP($this->file_temp, $this->file_name) == true) {
            $this->set_error('upload_invalid_filedangerous', 'debug');

            return false;
        }

        // if we're overriding, let's now make sure the new name and type is allowed
        if ($this->_file_name_override !== '') {
            $this->file_name = $this->_prep_filename($this->_file_name_override);

            // If no extension was provided in the file_name config item, use the uploaded one
            if (strpos($this->_file_name_override, '.') === false) {
                $this->file_name .= $this->file_ext;
            } else {
                // An extension was provided, let's have it!
                $this->file_ext = $this->get_extension($this->_file_name_override);
            }

            if (! $this->is_allowed_filetype(true)) {
                $this->set_error('upload_invalid_filetype', 'debug');

                return false;
            }
        }

        // Convert the file size to kilobytes
        if ($this->file_size > 0) {
            $this->file_size = round($this->file_size / 1024, 2);
        }

        // Is the file size within the allowed maximum?
        if (! $this->is_allowed_filesize()) {
            $this->set_error('upload_invalid_filesize', 'info');

            return false;
        }

        // Are the image dimensions within the allowed size?
        // Note: This can fail if the server has an open_basedir restriction.
        if (! $this->is_allowed_dimensions()) {
            $this->set_error('upload_invalid_dimensions', 'info');

            return false;
        }

        // Sanitize the file name for security
        $this->file_name = $this->_CI->security->sanitize_filename($this->file_name);

        // Truncate the file name if it's too long
        if ($this->max_filename > 0) {
            $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
        }

        // Remove white spaces in the name
        if ($this->remove_spaces === true) {
            $this->file_name = preg_replace('/\s+/', '_', $this->file_name);
        }

        if ($this->file_ext_tolower && ($ext_length = strlen($this->file_ext))) {
            // file_ext was previously lower-cased by a get_extension() call
            $this->file_name = substr($this->file_name, 0, -$ext_length) . $this->file_ext;
        }

        /*
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;
        if (false === ($this->file_name = $this->set_filename($this->upload_path, $this->file_name))) {
            return false;
        }

        /*
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file. Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean && $this->do_xss_clean() === false) {
            $this->set_error('upload_unable_to_write_file', 'error');

            return false;
        }

        /*
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first. If that fails
         * we'll use move_uploaded_file(). One of the two should
         * reliably work in most environments
         */
        if (! @copy($this->file_temp, $this->upload_path . $this->file_name) && ! @move_uploaded_file($this->file_temp, $this->upload_path . $this->file_name)) {
            $this->set_error('upload_destination_error', 'error');

            return false;
        }

        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image). We use this information
         * in the "data" function.
         */
        $this->set_image_properties($this->upload_path . $this->file_name);

        return true;
    }
}
