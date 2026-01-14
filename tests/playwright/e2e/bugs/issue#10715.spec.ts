import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Tanggal Perkawinan/Perceraian pada hasil Unduhan Tombol Unduh dan Unduh F1.09 muncul di anggota yang status Perkawinan nya Cerai Mati #10715', () => {
  test('fix: perbaikan Tanggal Perkawinan/Perceraian pada hasil Unduhan Tombol Unduh dan Unduh F1.09', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10715',
    },
  }, async ({ page }) => {
    await page.goto('keluarga/kartu_keluarga/1');
    await expect(page.locator('#mainform')).toContainText('No Status Perkawinan Tanggal Perkawinan / Perceraian Status Hubungan Dalam Keluarga Kewarganegaraan No. Paspor No. KITAS / KITAP Nama Ayah Nama Ibu 1 KAWIN TERCATAT 01-01-2026 KEPALA KELUARGA WNI - - ARFAH RAISAH 2 BELUM KAWIN - ANAK WNI - - AHLUL RUSDAH 3 CERAI MATI 02-01-2026 ANAK WNI - - AHLUL RUSDAH 4 CERAI MATI - ANAK WNI - - AHLUL RUSDAH Kalitapen, 13 Januari 2026 KEPALA KELUARGA KEPALA DESA KALITAPEN AHLUL MUHAMMAD ILHAM');
  });
});
