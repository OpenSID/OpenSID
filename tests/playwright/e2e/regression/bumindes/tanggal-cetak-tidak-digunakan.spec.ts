// Issue: #10974 — Tanggal cetak yang dipilih tidak digunakan pada hasil cetak buku administrasi umum
// https://github.com/OpenSID/OpenSID/issues/10974
import { test, expect } from '../../../utils/regressionTest';

// Nama bulan Indonesia untuk membangun expectedDateStr sesuai format tgl_indo() PHP
const BULAN_ID = ['', 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
                  'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];


test.describe('Bug/error: Tanggal cetak yang dipilih tidak digunakan pada hasil cetak di Buku Administrasi Umum (selalu menampilkan tanggal hari ini) #10974', () => {
  test('fix: perbaiki tanggal cetak yang dipilih tidak digunakan pada cetak', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/#10974',
    },
  }, async ({ page }) => {
    // Gunakan tanggal tetap agar test reprodusibel tanpa bergantung pada kalender
    const selectedDate = '01-01-2024';
    const [day, month, year] = selectedDate.split('-').map(Number);
    const expectedDateStr = `${String(day).padStart(2, '0')} ${BULAN_ID[month]} ${year}`;

    await page.goto('bumindes_tanah_kas_desa');

    // Buka dropdown Cetak/Unduh dan klik Cetak
    await page.locator('a.btn.dropdown-toggle').click();
    await page.getByRole('link', { name: ' Cetak' }).click();

    // Isi tanggal cetak via input langsung (lebih andal daripada klik kalender)
    await expect(page.locator('#modalBox')).toBeVisible({ timeout: 10000 });
    await page.locator('#tgl_1').fill(selectedDate);

    // Submit form dan tunggu popup cetak
    const page1Promise = page.waitForEvent('popup');
    await page.locator('#form-cetak').getByText('Cetak', { exact: true }).click();
    const page1 = await page1Promise;

    // Verifikasi tanggal yang dipilih muncul di hasil cetak
    await page1.waitForLoadState('networkidle');
    const bodyContent = await page1.locator('iframe[name="print-frame"]').contentFrame().locator('body').textContent({ timeout: 15000 });
    expect(bodyContent).toContain(expectedDateStr);

    // Pastikan tanggal hari ini TIDAK dipakai jika berbeda dari tanggal dipilih
    const today = new Date();
    const todayStr = `${String(today.getDate()).padStart(2, '0')} ${BULAN_ID[today.getMonth() + 1]} ${today.getFullYear()}`;
    if (todayStr !== expectedDateStr) {
      expect(bodyContent).not.toContain(todayStr);
    }
  });
});
