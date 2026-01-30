import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Typo Pekerjaaan pada saat Tambah Data Penduduk #10778', () => {
  test('fix: perbaikan typo Pekerjaaan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10778',
    },
  }, async ({ page }) => {
    await page.goto('penduduk/form_peristiwa/1');
    await expect(page.getByText('Pekerjaan')).toBeVisible();
  });
});
