import { test, expect } from '@playwright/test';
import path from 'path';
import { Laravel } from '@test/utils/laravel';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.beforeAll(async () => {
  // Cleanup: Hapus produk test jika ada
  await Laravel.query(
    "DELETE FROM `produk` WHERE `nama` = 'Produk Test Issue 11210' AND `config_id` = 1"
  );

  // Setup: Ambil pelapak pertama yang aktif
  const pelapakList = await Laravel.select(
    `SELECT pelapak.id, p.nama as pelapak, 
            (SELECT COUNT(pr.id) FROM produk pr WHERE pr.id_pelapak = pelapak.id) as jumlah
     FROM pelapak 
     LEFT JOIN tweb_penduduk p ON pelapak.id_pend = p.id
     WHERE pelapak.config_id = 1 AND pelapak.status = 1
     LIMIT 1`
  );

  if (pelapakList.length === 0) {
    throw new Error('Tidak ada pelapak aktif untuk test');
  }

  const pelapakId = pelapakList[0].id;

  // Insert produk test untuk pelapak jika belum ada
  await Laravel.query(
    `INSERT INTO \`produk\` 
     (\`config_id\`, \`id_pelapak\`, \`nama\`, \`harga\`, \`status\`) 
     VALUES (1, ?, 'Produk Test Issue 11210', 50000, 1)`,
    [pelapakId]
  );
});

test.describe('Bug/error: Pelapak yang sudah memiliki produk masih bisa dihapus lewat fitur select #11210', () => {
  test('fix: Pelapak dengan produk tidak bisa dihapus via hapus massal dengan error message', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11210',
    },
  }, async ({ page }) => {
    // 1. Navigasi ke halaman pelapak
    await page.goto('lapak_admin/pelapak');
    await expect(page.getByRole('heading', { name: /Pelapak/ })).toBeVisible();

    // 2. Tunggu DataTable tersedia
    await page.waitForSelector('#tabel-pelapak tbody tr');
    await page.waitForTimeout(1000);

    // 3. Cari row dengan produk test (harus ada karena di beforeAll insert)
    const row = page.locator('#tabel-pelapak tbody tr').filter({
      hasText: 'Produk Test Issue 11210',
    }).first();

    // 4. Verifikasi: Tombol hapus di aksi harus tidak ada (hidden)
    const deleteButtonInRow = row.locator('a[data-href*="pelapak_delete"], a[title*="Hapus"]');
    await expect(deleteButtonInRow).toHaveCount(0);

    // 5. Check checkbox pada row tersebut
    const checkbox = row.locator('input[type="checkbox"][name="id_cb[]"]');
    await expect(checkbox).toBeVisible();
    await checkbox.check();
    await expect(checkbox).toBeChecked();

    // 6. Klik tombol Hapus (hapus massal)
    const deleteAllButton = page.locator('a.hapus-terpilih');
    await expect(deleteAllButton).toBeVisible();
    await deleteAllButton.click();

    // 7. Modal konfirmasi muncul, isi input dengan "HAPUS"
    const modal = page.locator('#confirm-delete');
    await expect(modal).toBeVisible();

    const confirmInput = modal.locator('#confirm-input');
    await expect(confirmInput).toBeFocused();
    await confirmInput.fill('HAPUS');

    const okButton = modal.locator('#ok-delete');
    await expect(okButton).not.toBeDisabled();

    // 8. Klik tombol Hapus di modal
    await okButton.click();

    // 9. Verifikasi: Error notifikasi harus tampil dengan pesan konsisten
    // Tunggu halaman update dan notifikasi error muncul
    await page.waitForSelector('.alert.alert-danger, .notification-box.alert-danger', { timeout: 10000 });

    const errorAlert = page.locator('.alert.alert-danger, .notification-box.alert-danger').first();
    await expect(errorAlert).toBeVisible();

    // 10. Verifikasi isi pesan error harus konsisten dengan pesan di delete single
    const errorText = await errorAlert.textContent();
    expect(errorText).toContain('Terdapat pelapak yang masih memiliki produk');
    expect(errorText).toContain('Silakan hapus produk terlebih dahulu');

    // 11. Verifikasi: Pelapak masih ada di tabel (tidak terhapus)
    const rowAfterDelete = page.locator('#tabel-pelapak tbody tr').filter({
      hasText: 'Produk Test Issue 11210',
    });
    await expect(rowAfterDelete).toHaveCount(1);
  });
});
