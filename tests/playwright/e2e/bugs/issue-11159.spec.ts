import { test, expect } from '@playwright/test';
import path from 'path';

const adminFile = path.resolve(__dirname, '../../storage/auth/admin.json');

test.describe('Validasi Modal Ekspor Dokumen #11159', () => {

  test.use({ storageState: adminFile });

  test('menampilkan modal ekspor saat tombol ekspor diklik', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11159',
    },
  }, async ({ page }) => {
    // 1. Pergi ke halaman dokumen
    await page.goto('dokumen');

    // 2. Klik tombol Ekspor
    // Menggunakan regex untuk memastikan mencocokkan teks "Ekspor" secara tepat
    const btnEkspor = page.getByRole('link', { name: /Ekspor/i });
    await expect(btnEkspor).toBeVisible();
    await btnEkspor.click();

    // 3. Pastikan modal tampil
    // ModalBox biasanya memiliki judul yang sesuai dengan data-title
    const modalTitle = page.locator('#modalBox .modal-title');
    await expect(modalTitle).toBeVisible();
    await expect(modalTitle).toHaveText('Ekspor');

    // Pastikan konten di dalam modal juga termuat
    const modalBody = page.locator('#modalBox .modal-body');
    await expect(modalBody).toBeVisible();
    await expect(modalBody).toContainText('Ekspor data dan dokumen informasi publik');
  });

});
