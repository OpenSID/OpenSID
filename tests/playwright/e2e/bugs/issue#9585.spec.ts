import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Halaman "Lupa PIN" pada Layanan Mandiri Tidak Ditemukan #9585', () => {
  test('fix: lupa pin layanan mandiri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9585'
    }
  }, async ({ page }) => {
    await page.goto('setting_mandiri');
    await expect(page.locator('#form_layanan_mandiri')).toContainText('Layanan Mandiri Ya Tidak Tidak Apakah layanan mandiri ditampilkan atau tidak');
    await page.goto('http://127.0.0.1:8000/layanan-mandiri/lupa-pin');
    await expect(page.locator('h2')).toContainText('404');
    await page.goto('http://127.0.0.1:8000/setting_mandiri');
    await page.locator('#select2-layanan_mandiri-container').click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.goto('http://127.0.0.1:8000/layanan-mandiri/lupa-pin');
    await page.getByText('Terima semua cookie', { exact: true }).click();
    await expect(page.locator('h1')).toContainText('LAYANAN MANDIRI');
    await page.goto('http://127.0.0.1:8000/setting_mandiri');
    await page.getByRole('textbox', { name: 'Ya' }).click();
    await page.getByRole('treeitem', { name: 'Tidak' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
  });
});
