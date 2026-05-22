import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.describe('Identitas Desa - Upload Kantor Desa', () => {
  test('berhasil unggah file Kantor Desa dan simpan tanpa error', async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('identitas_desa/form');
    await page.waitForLoadState('networkidle');

    const fileKantorDesa = page.locator('input[type="file"][name="kantor_desa"]');
    await expect(fileKantorDesa).toHaveCount(1);

    await fileKantorDesa.setInputFiles(path.resolve(__dirname, '../../../../../assets/files/logo/opensid_kantor.jpg'));

    const tombolSimpan = page.locator('form#validasi button[type="submit"]').first();
    await expect(tombolSimpan).toBeVisible();
    await expect(tombolSimpan).toBeEnabled();

    await tombolSimpan.click();

    const modalError = page.getByText('Gagal Ubah Data');
    const berhasilRedirect = page.waitForURL(/identitas_desa(?:$|\?)/, { timeout: 15000 }).then(() => 'redirect' as const);
    const gagalModal = modalError.waitFor({ state: 'visible', timeout: 15000 }).then(() => 'error' as const).catch(() => null);

    const hasil = await Promise.race([berhasilRedirect, gagalModal]);

    expect(hasil).not.toBe('error');
    await expect(modalError).not.toBeVisible();
  });
});
