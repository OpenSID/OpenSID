import { test, expect } from '@playwright/test';

test.describe('Bug/error: Typo pada pesan error hapus Pemerintah Desa #10678', () => {
  test('fix: perbaikan Typo pada pesan error hapus Pemerintah Desa', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10678',
    },
  }, async ({ page }) => {
    await page.request.get('pengurus');
    await expect(page).toHaveURL(/.*\/pengurus/, { timeout: 10000 });
    await expect(page.getByRole('heading', { name: 'Buku Pemerintah Desa' })).toBeVisible();

    // 2. Cari baris pertama pada tabel, lalu klik tombol "Hapus".
    //    Asumsinya adalah pamong pertama (biasanya Kepala Desa) tidak dapat
    //    dihapus karena memiliki data-data terkait (seperti surat atau kehadiran).
    const firstRow = page.locator('table.table-bordered tbody tr').first();
    const deleteButton = firstRow.locator('a[title="Hapus"]');
    await expect(deleteButton).toBeVisible();
    await deleteButton.click();

    // 3. Tunggu modal konfirmasi penghapusan muncul, lalu klik tombol "Hapus" di dalam modal.
    const confirmModal = page.locator('#confirm-delete');
    await expect(confirmModal).toBeVisible();
    const confirmDeleteButton = confirmModal.getByRole('link', { name: 'Hapus' });
    await confirmDeleteButton.click();

    // 4. Verifikasi pesan kesalahan yang ditampilkan setelah mencoba menghapus.
    const errorAlert = page.locator('.alert-danger');
    await expect(errorAlert).toBeVisible({ timeout: 15000 });

    // 5. Pastikan pesan kesalahan berisi teks yang benar dan tidak mengandung typo.
    const expectedMessage = 'tidak dapat dihapus, data sudah tersedia di kehadiran perangkat, pengaduan kehadiran dan layanan Surat.';
    
    await expect(errorAlert).toContainText(expectedMessage);
    await expect(errorAlert).not.toContainText('perangkatl');
  });
});
