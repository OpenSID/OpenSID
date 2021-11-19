<?php


/**
 * Menghasilkan csv dari data tabel
 * Baris pertama berisi nama kolom
 * Saat ini pemisah menggunakan ','
 * Acuan: https://stackoverflow.com/questions/4249432/export-to-csv-via-php
 *
 * @access  public
 * @param	string	nama tabel yang akan diekspor
 * @return  string
 */
function tulis_csv($table)
{
	$CI =& get_instance();
	$CI->load->database();

	$data = $CI->db->get($table)->result_array();
	if (count($data) == 0)
		 return null;

	ob_start();
	$df = fopen("php://output", 'w');
	fputcsv($df, array_keys(reset($data)));
	foreach ($data as $row)
	{
		fputcsv($df, $row);
	}
	fclose($df);
	return ob_get_clean();
}

/**
  https://stackoverflow.com/questions/7391969/in-memory-download-and-extract-zip-archive
  https://www.php.net/manual/en/function.str-getcsv.php
  https://bugs.php.net/bug.php?id=55763

  Contoh yg dihasilkan:

  Array
  (
      [0] => Array
          (
              [Kd_Bid] => 01
              [Nama_Bidang] => Bidang Penyelenggaraan Pemerintah Desa
          )

      [1] => Array
          (
              [Kd_Bid] => 02
              [Nama_Bidang] => Bidang Pelaksanaan Pembangunan Desa
          )
  )
*/
function get_csv($zip_file, $file_in_zip)
{
  # read the file's data:
  $path = sprintf('zip://%s#%s', $zip_file, $file_in_zip);
  $file_data = file_get_contents($path);
  //$file_data = preg_split('/[\r\n]{1,2}(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $file_data);
  $file_data = preg_split('/\r*\n+|\r+/', $file_data);
  $csv = array_map('str_getcsv', $file_data);
  array_walk($csv, function(&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
  });
  array_shift($csv); # remove column header
  return($csv);
}

/**
 * Paksa download file
 *
 * @access  public
 * @param	string	nama file untuk didownload
 * @return  string
 */
function download_send_headers($filename)
{
	// disable caching
	$now = gmdate("D, d M Y H:i:s");
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	header("Last-Modified: {$now} GMT");

	// force download
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	// disposition / encoding on response body
	header("Content-Disposition: attachment;filename={$filename}");
	header("Content-Transfer-Encoding: binary");
}

function duplicate_key_update_str($data)
{
	$update_str = '';
	foreach ($data as $key => $item)
	{
			$update_str .= $key.'=VALUES('.$key.'),';
	}
	$update_str = ' ON DUPLICATE KEY UPDATE ' . rtrim($update_str, ', ');
	return $update_str;
}
?>