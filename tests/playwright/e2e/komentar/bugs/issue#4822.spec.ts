import { test, expect } from '@playwright/test';
import { Laravel } from '../../../laravel';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Tombol Batal Form Komentar (#4822)', () => {
  test('fix: Tombol Batal Form Komentar', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/4822',
    },
  }, async ({ page }) => {
    await page.goto('komentar');
    await page.getByRole('textbox', { name: 'Aktif' }).click();
    await page.getByRole('treeitem', { name: 'Semua' }).click();
    // pilih edit salah satu komentar
    await page.getByRole('link', { name: '' }).click();
    // pilih status aktif
    await page.getByText('Aktif', { exact: true }).click();
    // klik tombol reset
    await page.getByRole('button', { name: ' Batal' }).click();
    // validasi status kembali ke pilihan sebelumnya (tidak aktif)
    const status = await page.getByRole('radio', { name: 'Tidak Aktif' }).isChecked();
    expect(status).toBeTruthy();
  });
});
