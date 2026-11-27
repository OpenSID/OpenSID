// Issue: #10958 — Pesan sukses hapus data dokumen penduduk
// https://github.com/OpenSID/OpenSID/issues/10958
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';


test.describe('Bug/error: Pesan Sukses Hapus Data Dokumen Penduduk #10958', () => {
  test('fix: perbaikan pesan sukses hapus data dokumen penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10958',
    },
  }, async ({ page }) => {
    // Navigate to the dokumen page for penduduk id=98
    await page.goto('penduduk/dokumen/98');
    await page.waitForSelector('#maincontent', { timeout: 10000 });

    // Upload a document first so we have something to delete
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.getByPlaceholder('Nama Dokumen').fill('hapus-test-dokumen');
    await page.locator('#id_syarat').selectOption('1');
    const filePath = path.resolve(__dirname, '../../../storage/fixtures/dummy.pdf');
    await page.waitForSelector('#file_path');
    await page.locator('#file').setInputFiles(filePath);
    await page.locator('#ok').click();
    await expect(page.locator('#notifikasi')).toContainText('Dokumen berhasil disimpan', { timeout: 10000 });

    // Now delete the document we just uploaded
    await page.locator('a.btn.bg-maroon[data-target="#confirm-delete"]').first().click();
    await expect(page.locator('#confirm-delete .modal-content')).toBeVisible();

    // Wait for modal animation + resetModal() to complete before filling
    await page.waitForFunction(
      () => document.querySelector('#confirm-input') === document.activeElement,
      { timeout: 5000 }
    );
    await page.locator('#confirm-input').fill('HAPUS');
    await expect(page.locator('#confirm-delete #ok-delete')).not.toBeDisabled();
    await page.locator('#confirm-delete .btn-ok').click();

    // Verify success notification
    await expect(page.locator('body')).toContainText('Dokumen/data berhasil dihapus', { timeout: 10000 });
  });
});
