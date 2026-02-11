import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Editing Bantuan program fails when peserta count is 0 #10832', () => {
  
  test('should create and edit bantuan program successfully', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10832',
    },
  }, async ({ page }) => {
    const testName = 'Program Bantuan Test ' + Date.now();
    const today = new Date().toLocaleDateString('id-ID');

    // ===== STEP 1: Buat Program Bantuan Baru =====
    // 1.1 Go to program_bantuan page
    await page.goto('/program_bantuan');
    await expect(page.locator('h1')).toContainText('Program Bantuan');
    
    // 1.2 Click tombol "Tambah" (look for button with specific text in the page)
    // Usually the button is in the header or toolbar
    const tambahButton = page.locator('a, button').filter({ hasText: /Tambah|Add/ }).first();
    if (!await tambahButton.isVisible({ timeout: 2000 }).catch(() => false)) {
      // Alternative: navigate directly to create page
      await page.goto('/program_bantuan/create?cid=1');
    } else {
      await tambahButton.click();
    }
    
    // 1.3 Fill the form
    // Sasaran
    await page.locator('select[name="cid"]').selectOption('1');
    
    // Nama Program
    await page.locator('input[name="nama"]').fill(testName);
    
    // Keterangan
    await page.locator('textarea[name="ndesc"]').fill('Test description untuk program bantuan');
    
    // Asal Dana
    await page.locator('select[name="asaldana"]').selectOption({ index: 1 }); // Select first non-empty option
    
    // Rentang Waktu
    await page.locator('input[name="sdate"]').fill(today);
    await page.locator('input[name="edate"]').fill(today);
    
    // Publikasi
    await page.locator('select[name="publikasi"]').selectOption({ index: 1 }); // Select first option (Aktif)
    
    // 1.4 Click tombol Simpan
    await page.getByRole('button', { name: /Simpan/ }).click();
    
    // 1.5 Verify success message and redirect to program_bantuan page
    await expect(page.locator('div.alert.alert-success')).toBeVisible({ timeout: 5000 });
    await expect(page).toHaveURL(/.*program_bantuan/, { timeout: 5000 });

    // ===== STEP 2: Edit Program Bantuan =====
    // 2.1 Wait for table to load and find the program we just created
    await page.waitForTimeout(500);
    
    // Find the row containing our test program
    const row = page.getByRole('row').filter({ hasText: testName });
    
    // 2.2 Click tombol "Ubah Data" (atau "Ubah" / "Edit")
    const editButton = row.locator('a, button').filter({ hasText: /Ubah|Edit/ }).first();
    await editButton.click();
    
    // 2.3 Verify we're on the edit page
    await expect(page.locator('form#validasi')).toBeVisible();
    await expect(page).toHaveURL(/.*program_bantuan.*(edit|form)/);

    // ===== STEP 3: Submit Form =====
    // 3.1 Click tombol Simpan (tanpa mengubah apapun - test that it still works with 0 peserta)
    await page.getByRole('button', { name: /Simpan/ }).click();
    
    // 3.2 Verify redirect back to program_bantuan page with success notification "Berhasil Ubah Data"
    await expect(page.locator('div.alert.alert-success')).toContainText(/Berhasil.*Ubah.*Data|sukses/i);
    await expect(page).toHaveURL(/.*program_bantuan[^\/]*$/, { timeout: 5000 });
    
    // ===== CLEANUP =====
    // Delete the test program
    await page.waitForTimeout(500);
    const deleteRow = page.getByRole('row').filter({ hasText: testName });
    const deleteButton = deleteRow.locator('a, button').filter({ hasText: /Hapus|Delete/ }).first();
    
    if (await deleteButton.isVisible({ timeout: 1000 }).catch(() => false)) {
      await deleteButton.click();
      // Handle delete confirmation modal if it exists
      const confirmBtn = page.locator('a.btn-ok, button.btn-ok').first();
      if (await confirmBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
        await confirmBtn.click();
      }
    }
  });
});
