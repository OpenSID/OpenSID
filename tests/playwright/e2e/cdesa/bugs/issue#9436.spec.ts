import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Form Pengelolaan Data C-Desa #9436', () => {
  test('fix: perbaikan form cdesa tidak sesuai', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9436',
    },
  }, async ({ page }) => {
    await page.goto('cdesa');
    await page.getByRole('link', { name: '', exact: true }).click();
    await page.getByRole('textbox', { name: '-- Pilih Nomor Persil --' }).click();
    await page.getByRole('treeitem', { name: ': 37 - TEBAT JAYA' }).click();
    await expect(page.getByText('Nomor Bidang Mutasi Luas')).toBeVisible();

    // klik kembali link "Mutasi - Bidang Tanah"
    await page.getByRole('link', { name: 'Mutasi - Bidang Tanah' }).click();
    await page.getByRole('link', { name: 'Mutasi - Bidang Tanah' }).click();

    // pastikan Nomor Bidang Mutasi Luas tidak ada
    const panel = await page.locator('#bidang_persil');
    await expect(panel).toHaveClass(/panel-collapse collapse/);
  });
});
