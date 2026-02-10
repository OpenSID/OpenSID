import { test, expect } from '@playwright/test';

test.describe('Bug/error: proses pendaftaran layanan mandiri gagal, muncul blank. eror yang tampil tidak sesuai dengan yang ada di log #10825', () => {
  test('fix: perbaiki validasi email dan telegram pada pendaftaran layanan mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10825',
    },
  }, async ({ page }) => {
    await page.goto('/layanan-mandiri/daftar');

    const filePath = require.resolve('@test/storage/fixtures/unduh_qrcode_173.png');

    await page.getByRole('textbox', { name: 'Nama' }).fill('RONI');
    await page.getByRole('textbox', { name: 'NIK' }).fill('5201141012849835');
    await page.getByRole('textbox', { name: 'KK' }).fill('5201140211111494');
    await page.getByRole('textbox', { name: 'Tanggal Lahir' }).fill('19831210');
    await page.getByRole('textbox', { name: 'Email' }).fill('roni@gmail.com');
    await page.getByRole('textbox', { name: 'Telegram' }).fill('123456788');
    await page.getByRole('textbox', { name: 'PIN', exact: true }).fill('123456');
    await page.getByRole('textbox', { name: 'Konfirmasi PIN' }).fill('123456');
    await page.locator('input[name="scan_1"]').setInputFiles(filePath);
    await page.locator('input[name="scan_2"]').setInputFiles(filePath);
    await page.locator('input[name="scan_3"]').setInputFiles(filePath);

    await page.getByRole('button', { name: 'BUAT AKUN' }).click();
    await expect(page.locator('body')).toContainText('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda melakukan verifikasi Email dan Telegram Anda dengan mengklik tautan yang baru saja kami kirimkan kepada Anda? Jika Anda tidak menerima notifikasi tersebut, kami akan dengan senang hati mengirimkan yang lain.');
  });
});
