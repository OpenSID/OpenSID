import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Modul Beranda', () => {
  test('Menampilkan halaman beranda', async ({ page }) => {
    await page.goto('beranda');
    await expect(page.getByRole('link', { name: 'Beranda' }).first()).toBeVisible();
    await expect(page.getByRole('heading', { name: 'Tentang OpenSID' })).toBeVisible();
    await expect(page.locator('#accordion')).toContainText('Aplikasi OpenSID');
    await expect(page.locator('#collapse1')).toContainText('OpenSID adalah aplikasi Sistem Informasi Desa');
  });
});