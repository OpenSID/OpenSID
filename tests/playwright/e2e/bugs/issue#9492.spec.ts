import { test, expect} from '@playwright/test';
import path from 'path';
import { Laravel } from '../../laravel';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.beforeAll(async () => {
  await Laravel.query(`
    INSERT INTO ref_jabatan (config_id, nama, tupoksi, jenis, created_at, updated_at) VALUES
      (1, 'Hapus 1', 'Tupoksi Hapus 1', 0, '2025-04-11 07:21:27', '2025-04-11 07:22:17'),
      (1, 'Hapus 2', 'Tupoksi Hapus 2', 0, '2025-04-11 07:21:27', '2025-04-11 07:22:17'),
      (1, 'Hapus 3', 'Tupoksi Hapus 3', 0, '2025-04-11 07:21:27', '2025-04-11 07:22:17');
  `, [], { unprepared: true });

  await Laravel.artisan('cache:clear');
});

test.describe('Bug/error: Error hapus massal Daftar Jabatan Pengurus #9492', () => {
  test('fix: perbaiki hapus masal daftar jabatan pengurus', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9492'
    }
  }, async ({ page }) => {
    await page.goto('pengurus/jabatan');

    await expect(page.getByRole('gridcell', { name: 'Sekretaris Desa' })).toBeVisible();
    await page.locator('#checkall').check();
    await expect(page.getByRole('link', { name: ' Hapus' })).toBeVisible();
    await page.getByRole('link', { name: ' Hapus' }).click();
    await expect(page.getByText('Apakah Anda yakin ingin')).toBeVisible();
    await page.locator('#confirm-delete a').click();
    await expect(page.getByText('Berhasil Hapus Data')).toBeVisible();
    await expect(page.getByRole('gridcell', { name: 'Sekretaris Desa' })).toBeVisible();
  });
});