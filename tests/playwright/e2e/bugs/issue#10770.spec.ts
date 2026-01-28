import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('bug: eror tidak bisa cetak surat dinas jika nomor surat manual lebih dari 35 karakter #10770', () => {
  test('fix: perbaikan validasi format nomor surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10770',
    },
  }, async ({ page }) => {
    await page.goto('surat_dinas/form');
    await page.locator('#lmg21').click();
    const input = page.getByRole('textbox', { name: '[nomor_surat, 3]/PK-TBT/[' });
    await expect(input).toHaveAttribute('maxlength', '35');
    await input.fill('q'.repeat(50));
    await expect(input).toHaveValue('q'.repeat(35));
  });
});
