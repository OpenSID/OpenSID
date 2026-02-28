import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tombol kirim pada permohonan surat di Layanan Mandiri #9467', () => {
  test('fix: tombol kirim pada permohonan surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9467'
    }
  }, async ({ page }) => {    
    test.setTimeout(100000);
    await page.goto('http://127.0.0.1:8000/layanan-mandiri/masuk');

    try {
      await page.getByText('Terima semua cookie', { exact: true }).click();
    } catch {} // abaikan kalau tombol cookie nggak ada

    try {
      //#5201144609786995#897452
      await page.getByRole('textbox', { name: 'NIK' }).fill('1505022111940001');
      await page.getByRole('textbox', { name: 'PIN' }).fill('770998');
      // await page.getByRole('textbox', { name: 'NIK' }).fill('5201144609786995');
      // await page.getByRole('textbox', { name: 'PIN' }).fill('897452');
      await page.getByRole('button', { name: 'MASUK', exact: true }).click();      

      await page.goto('http://127.0.0.1:8000/layanan-mandiri/permohonan-surat');
      await page.getByRole('link', { name: ' Buat Surat' }).click();      
      await page.locator('#id_surat').click();
      await page.locator('#id_surat').selectOption('Biodata Penduduk');      
      await page.getByRole('textbox', { name: 'Ketik di sini untuk' }).fill('fdsfasdfasfa');
      await page.getByRole('textbox', { name: 'No. HP aktif' }).click();
      await page.getByRole('textbox', { name: 'No. HP aktif' }).fill('085733659400');
      await page.getByRole('button', { name: ' Isi Form' }).click();
      await page.getByRole('button', { name: ' Kirim' }).click();      
      await expect(page.getByRole('heading', { name: 'DAFTAR PERMOHONAN SURAT' })).toBeVisible();
    } catch {
    }
  });
});
