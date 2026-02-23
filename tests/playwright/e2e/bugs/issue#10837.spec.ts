import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Permintaan fitur: Hapus/ubah diksi pada checklist import peserta ketika import bantuan #10837', () => {
  test('fitur: tambahkan penjelasan pada checklist impor program bantuan', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/10837'
    }
  }, async ({ page }) => {
    await page.goto('program_bantuan');
    await page.getByRole('link', { name: ' Impor' }).click();

    // Assert penjelasan checklist program
    await expect(page.getByLabel('Ganti data lama jika data ditemukan sama')).toBeVisible();
    await expect(page.getByText('Centang jika ingin memperbarui data program bantuan lama yang memiliki nama/program sama dengan data yang diimpor. Jika tidak dicentang, data lama tidak diubah dan data baru dengan nama/program sama diabaikan.')).toBeVisible();

    // Assert penjelasan checklist peserta
    await expect(page.getByLabel('Kosongkan data peserta sebelum impor')).toBeVisible();
    await expect(page.getByText('Centang jika ingin menghapus semua data peserta lama sebelum data baru diimpor')).toBeVisible();

    await expect(page.getByLabel('Ganti data peserta lama jika NIK sama')).toBeVisible();
    await expect(page.getByText('Centang jika ingin memperbarui data peserta lama yang memiliki NIK sama')).toBeVisible();

    await expect(page.getByLabel('Acak nomor kartu peserta jika kosong')).toBeVisible();
    await expect(page.getByText('Centang jika ingin sistem mengisi otomatis nomor kartu peserta secara acak')).toBeVisible();
  });
});