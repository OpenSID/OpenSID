import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '@test/utils/laravel';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.beforeAll(async () => {
  await Laravel.query(
    "INSERT INTO `tweb_rtm` (`config_id`, `nik_kepala`, `no_kk`, `tgl_daftar`, `kelas_sosial`, `bdt`, `terdaftar_dtks`) VALUES (1, '1', '011405000123', '2020-07-30 04:36:37', NULL, NULL, '0')"
  );
});

test.describe('Permintaan fitur: Penanganan RTM yang memiliki kepala RTM sama #10596', () => {
  test('Menambahkan fitur periksa untuk penanganan RTM yang memiliki kepala RTM sama', async ({ page }) => {
    await page.goto('periksa');
    await expect(page.locator('body')).toContainText('Terdeteksi kepala RTM ganda');
    await page.getByRole('button', { name: ' Hapus' }).click();
    await expect(page.locator('body')).toContainText('Sudah dihapus');
  });
});
