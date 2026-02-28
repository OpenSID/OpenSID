import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '@test/utils/laravel';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

// test.beforeEach(async ({ page }) => {
//   await Laravel.query(`
//     INSERT INTO tweb_penduduk (foto, nik, nama, ktp_el, status_rekam, tag_id_card, tempat_cetak_ktp, tanggal_cetak_ktp, no_kk_sebelumnya, kk_level, sex, agama_id, status, akta_lahir, tempatlahir, tanggallahir, waktu_lahir, tempat_dilahirkan, jenis_kelahiran, kelahiran_anak_ke, penolong_kelahiran, berat_lahir, panjang_lahir, pendidikan_kk_id, pendidikan_sedang_id, pekerjaan_id, adat, suku, marga, warganegara_id, dokumen_pasport, tanggal_akhir_paspor, dokumen_kitas, negara_asal, ayah_nik, nama_ayah, ibu_nik, nama_ibu, id_cluster, alamat_sebelumnya, alamat_sekarang, telepon, email, telegram, hubung_warga, status_kawin, golongan_darah_id, cacat_id, sakit_menahun_id, cara_kb_id, hamil, id_asuransi, no_asuransi, status_asuransi, bpjs_ketenagakerjaan, bahasa_id, ket, tanggalperkawinan, tanggalperceraian, akta_perkawinan, akta_perceraian, created_by, updated_by, config_id, updated_at, created_at) VALUES ('', 1234567890123456, 'Mis Arianto', 3, 3, '', '', '', '', 1, 1, 1, 1, 1234567890123456, 'BIYAHAN', '2025-07-01', '14:54', 4, 1, 1, 3, 16, 45, 1, 18, 1, 'melayu', 'melayu', 'melayu', 1, '', '', '', '', 1234567890123457, 'Handoko', 1234567890123458, 'Teraya', 10032641, 'Jalan Banglas Gang Antara', 'Jalan Banglas Gang Antara', 085265942010, 'admin@mail.com', '', 'Email', 1, 1, 7, 14, '', '', 1, '', '', '', 1, '', '', '', '', '', 12926702, 12926702, 32, '2025-07-08 14:55:08', '2025-07-08 14:55:08');
//   `, [], { unprepared: true });

//   await Laravel.artisan('cache:clear');
// });

test.describe('Bug/error: Hapus Penduduk erorr #9776', () => {
  test('fix: perbaiki hapus penduduk yang gagal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9776',
    },
  }, async ({ page }) => {
    // await page.goto('penduduk');
    // await expect(page.getByText('Tambah Penduduk')).toBeVisible();
    // await page.getByText('Tambah Penduduk').click();
    // await page.getByRole('link', { name: ' Penduduk Lahir' }).click();
    // await page.getByRole('textbox', { name: 'NIK', exact: true }).click();
    // await page.getByRole('textbox', { name: 'NIK', exact: true }).fill('1234567890123456');
    // await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).click();
    // await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).press('CapsLock');
    // await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).fill('Mis Arianto');
    // await page.getByRole('textbox', { name: 'Nama Lengkap (Tanpa Gelar)' }).press('CapsLock');
    // await page.locator('select[name="status_rekam"]').selectOption('3');
    // await page.getByRole('textbox', { name: 'Pilih Hubungan Keluarga' }).click();
    // await page.getByRole('treeitem', { name: 'KEPALA KELUARGA' }).click();
    // await page.locator('select[name="sex"]').selectOption('1');
    // await page.locator('select[name="agama_id"]').selectOption('1');
    // await page.locator('#status_penduduk').selectOption('1');
    // await page.waitForSelector('input[name="akta_lahir"]', { timeout: 10000 });
    // await page.getByRole('textbox', { name: 'Nomor Akta Kelahiran' }).click();
    // await page.getByRole('textbox', { name: 'Nomor Akta Kelahiran' }).fill('1234567890123456');

    // await expect(page.getByRole('textbox', { name: 'Tempat Lahir' })).toBeVisible();
    // await page.waitForSelector('select[name="tempat_dilahirkan"]', { timeout: 10000 });
    // await page.getByRole('textbox', { name: 'Tempat Lahir' }).click();
    // await page.getByRole('textbox', { name: 'Tempat Lahir' }).fill('BIYAHAN');
    // await page.locator('#tgl_lahir').click();
    // await page.locator('#tgl_lahir').fill('2001-01-01');
    // await page.locator('#jammenit_1').click();
    // await page.waitForSelector('select[name="tempat_dilahirkan"]', { timeout: 10000 });
    // await page.locator('select[name="tempat_dilahirkan"]').selectOption('4');
    // await page.locator('select[name="jenis_kelahiran"]').selectOption('1');
    // await page.waitForSelector('input[name="kelahiran_anak_ke"]', { timeout: 10000 });
    // await page.locator('#kelahiran_anak_ke').click();
    // await page.locator('#kelahiran_anak_ke').fill('1');

    // await page.locator('select[name="penolong_kelahiran"]').selectOption('3');
    // await page.waitForSelector('input[name="berat_lahir"]', { timeout: 10000 });
    // await page.getByRole('textbox', { name: 'Berat Lahir ( Gram )' }).click();
    // await page.getByRole('textbox', { name: 'Berat Lahir ( Gram )' }).fill('16');
    // await page.locator('input[name="panjang_lahir"]').click({ timeout: 10000 });
    // await page.getByRole('textbox', { name: 'Panjang Lahir ( cm )' }).fill('23');
    // await page.getByLabel('Status Warga Negara').selectOption('1');

    // await page.waitForSelector('input[name="ayah_nik"]', { timeout: 10000 });
    // await page.getByRole('textbox', { name: 'NIK Ayah' }).click();
    // await page.getByRole('textbox', { name: 'NIK Ayah' }).fill('1234567890123457');
    // await page.waitForSelector('input[name="nama_ayah"]', { timeout: 10000 });
    // await page.getByRole('textbox', { name: 'Nama Ayah' }).click();
    // await page.getByRole('textbox', { name: 'Nama Ayah' }).fill('Handoko');
    // await page.waitForSelector('input[name="ibu_nik"]', { timeout: 20000 });
    // await page.getByRole('textbox', { name: 'NIK Ibu' }).click();
    // await page.getByRole('textbox', { name: 'NIK Ibu' }).fill('1234567890123458');

    // await page.waitForSelector('input[name="nama_ibu"]', { timeout: 50000 });
    // await page.getByRole('textbox', { name: 'Nama Ibu' }).click();
    // await page.getByRole('textbox', { name: 'Nama Ibu' }).fill('Teraya');

    // await page.getByRole('textbox', { name: 'Nomor Telepon' }).click();
    // await page.getByRole('textbox', { name: 'Nomor Telepon' }).fill('085265942010');
    // await page.locator('#status_perkawinan').selectOption('1');
    // await page.locator('select[name="golongan_darah_id"]').selectOption('1');
    // await page.locator('select[name="cacat_id"]').selectOption('7');
    // await page.locator('select[name="sakit_menahun_id"]').selectOption('14');
    // await page.locator('select[name="id_asuransi"]').selectOption('1');

    // // simpan penduduk
    // await page.locator('button:has-text("Simpan")').click();

    // await page.waitForURL('**/penduduk');

    // const notifikasi = page.locator('#notifikasi');
    // await expect(notifikasi).toBeVisible();
    // await expect(notifikasi).toContainText('Berhasil');
    // await expect(notifikasi).toContainText('Dokumen berhasil disimpan');

    // // hapus penduduk
    // await expect(page.getByRole('row', { name: '2  Pilih Aksi Foto Penduduk' }).getByRole('checkbox')).toBeVisible();
    // await page.getByRole('row', { name: '2  Pilih Aksi Foto Penduduk' }).getByRole('checkbox').check();
    // await page.getByRole('link', { name: ' Hapus Data Terpilih' }).click();
    // await page.locator('#confirm-delete a').click();
    // await expect(page.locator('#notifikasi')).toBeVisible();
    // await expect(page.locator('#notifikasi')).toContainText('Berhasil');
    // await expect(page.locator('#notifikasi p')).toContainText('Penduduk berhasil dihapus');

    // await expect(page.getByRole('searchbox', { name: 'Cari:' })).toBeVisible();
    // await page.getByRole('searchbox', { name: 'Cari:' }).click();
    // await page.getByRole('searchbox', { name: 'Cari:' }).press('CapsLock');
    // await page.getByRole('searchbox', { name: 'Cari:' }).fill('Mis Arianto');
    // await page.locator('#checkall').check();
    // await page.getByRole('link', { name: ' Hapus Data Terpilih' }).click();
    // await page.locator('#confirm-delete a').click();
    // await expect(page.locator('#notifikasi')).toBeVisible();
    // await expect(page.locator('#notifikasi')).toContainText('Berhasil');
    // await expect(page.locator('#notifikasi p')).toContainText('Penduduk berhasil dihapus');

    await page.goto('penduduk');
    await expect(page.getByRole('row', { name: '2  Pilih Aksi Foto Penduduk' }).getByRole('checkbox')).toBeVisible();
    await page.getByRole('row', { name: '2  Pilih Aksi Foto Penduduk' }).getByRole('checkbox').check();
    await page.getByRole('link', { name: ' Hapus Data Terpilih' }).click();
    await page.locator('#confirm-delete a').click();
    await expect(page.locator('#notifikasi')).toBeVisible();
    await expect(page.locator('#notifikasi')).toContainText('Berhasil');
    await expect(page.locator('#notifikasi p')).toContainText('Penduduk berhasil dihapus');

  });
});