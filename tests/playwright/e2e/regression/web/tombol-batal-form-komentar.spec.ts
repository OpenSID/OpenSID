// Issue: #4822 — Tombol batal form komentar
// https://github.com/OpenSID/OpenSID/issues/4822
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '@test/utils/laravel';


test.beforeAll(async () => {
  await Laravel.query("INSERT INTO `komentar` (`config_id`, `id_artikel`, `owner`, `email`, `subjek`, `komentar`, `tgl_upload`, `status`, `tipe`, `no_hp`, `updated_at`, `is_archived`, `permohonan`, `jenis`, `parent_id`) VALUES (1, 110, 'pengunjung', 'pengunjung@gmail.com', NULL, 'test', '2025-04-08 08:20:23', 2, NULL, '082111111111', '2025-04-08 08:20:23', 0, NULL, NULL, NULL)");
});

test.afterAll(async () => {
  await Laravel.query("DELETE FROM `komentar` WHERE `email` = 'pengunjung@gmail.com'");
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
    await page.getByTitle('Ubah Data').first().click();
    // pilih status aktif
    await page.getByText('Aktif', { exact: true }).click();
    // klik tombol reset
    await page.getByRole('button', { name: ' Batal' }).click();
    // validasi status kembali ke pilihan sebelumnya (tidak aktif)
    const status = await page.getByRole('radio', { name: 'Tidak Aktif' }).isChecked();
    expect(status).toBeTruthy();
  });
});
