import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Duplikasi tombol hapus pada Kode Isian Alias #9513', () => {
  test('fix: perbaiki duplikasi tombol hapus pada kode isian alias', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9513'
    }
  }, async ({ page }) => {
    await page.goto('surat_master/pengaturan');

    await page.getByRole('link', { name: 'Kode Isian Alias' }).click();
    await page.getByText('Form Kode Isian').click();
    await expect(page.getByRole('heading', { name: 'Kode Isian Alias' })).toBeVisible();
    await page.waitForTimeout(300);
    const judulInput = page.locator('input[name="judul_kode_isian"]');
    await judulInput.waitFor({ state: 'visible' });
    await judulInput.fill('Judul');
    await page.waitForTimeout(300);
    const aliasInput = page.locator('input[name="alias_kode_isian"]');
    await aliasInput.waitFor({ state: 'visible' });
    await aliasInput.fill('judul');
    await page.locator('#editor-kodeisian_ifr').contentFrame().locator('html').click();
    await page.locator('#editor-kodeisian_ifr').contentFrame().getByLabel('Rich Text Area').fill('[Format_nomor_suraT]');
    await page.locator('#btn-tambah-alias').click();
    await expect(page.locator('td.aksi a.btn')).toHaveCount(2);
  });
});