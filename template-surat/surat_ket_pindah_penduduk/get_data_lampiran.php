<?php defined('BASEPATH') || exit('No direct script access allowed');

$this->load->model('pamong_model');

/**
 * Kembalikan nama file lampiran yang akan digunakan, di mana
 * Surat Keterangan Pindah Penduduk ada beberapa pilihan format dan
 * pengguna bisa memilih format mana yang akan digunakan.
 */

if (in_array($input['kode_format'], ['F-1.25', 'F-1.27'])) {
	$judul = 'Antar Desa/Kelurahan Dalam Satu Kecamatan';
} else if (in_array($input['kode_format'], ['F-1.29', 'F-1.31'])) {
	$judul = 'Antar Kecamatan Dalam Satu Kabupaten/Kota';
} else if (in_array($input['kode_format'], ['F-1.34', 'F-1.39'])) {
	$judul = 'Antar Kabupaten/Kota atau Antar Provinsi';
} else if ($input['kode_format'] == 'F-1.23') {
	$judul = 'Dalam Satu Desa/Kelurahan';
} else {
	// F-1.03 & F-1.08
	$judul = '';
}
$input['judul_format'] = $judul;

if ($input['pakai_format'] == 'f103') {
	$daftar_lampiran = [$daftar_lampiran[0]];
} elseif ($input['pakai_format'] == 'f108') {
	$daftar_lampiran = [$daftar_lampiran[1]];
} elseif ($input['pakai_format'] == 'f125') {
	$daftar_lampiran = [$daftar_lampiran[2]];
} else {
	$daftar_lampiran = [$daftar_lampiran[3]];
}

if ($input['pakai_format'] == 'f127' && $input['status_kk_pindah_id'] == 2) {
	$input['no_kk_baru'] = $input['nik_kk_baru'] = $input['nama_kk_baru'] = '';
} elseif ($input['pakai_format'] == 'f127' && $input['status_kk_pindah_id'] != 1) {
	$input['no_kk_baru'] = $individu['no_kk'];
	$input['nik_kk_baru'] = $individu['nik_kk'];
	$input['nama_kk_baru'] = $individu['kepala_kk'];
}

$kepala_desa = $this->pamong_model->get_pamong($this->input->post('pamong_id'));
