import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Element data kependudukan tidak tampil di Kartu Rumah Tangga #10717', () => {
  test('fix: perbaikan element data kependudukan tidak tampil di kartu rumah tangga', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10717',
    },
  }, async ({ page }) => {
    await page.goto('rtm/kartu_rtm/30');
    await expect(page.locator('#maincontent')).toContainText('No Nama Lengkap NIK Nomor KK Jenis Kelamin Tempat Lahir Tanggal Lahir Agama Pendidikan Pekerjaan 1 AHYAR 5201141003666996 5201140104126995 LAKI-LAKI JAKARTA 10 Maret 1965 ISLAM SLTA/SEDERAJAT WIRASWASTA');
    await expect(page.locator('#maincontent')).toContainText('No Status Perkawinan Status Hubungan Dalam Rumah Tangga Kewarganegaraan Nama Ayah Nama Ibu Golongan Darah 1 KAWIN BELUM TERCATAT Kepala Rumah Tangga WNI PAIMUN SUPINAH TIDAK TAHU');
  });
});
