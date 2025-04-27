import { test, expect} from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Isian Status KK pada lampiran surat F-1.03 #9486', () => {
  test('fix: lampiran f-1.03 anggota pindah dan tidak', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/#9486'
    }
  }, async ({ page }) => {
    const testData = {
      nik: '1505022308730003',
      name: 'RAHAMIN',
    };

    await test.step('Navigasi ke halaman form surat keterangan pindah penduduk', async () => {
      await page.goto('surat/form/sistem-surat-keterangan-pindah-penduduk');
    });

    await test.step('Pilih NIK dari dropdown', async () => {
      await page.getByText('-- Cari NIK / Tag ID Card / Nama Penduduk --').first().click();
      await page.locator('input[type="search"]').fill('a');
      await page.getByRole('treeitem', { name: `NIK/Tag ID Card : ${testData.nik} - ${testData.name}` }).click();
    });
    await page.getByRole('textbox', { name: 'Telepon Pemohon' }).fill('081356297444');
    await page.locator('select[name="gunakan_format"]').selectOption('F-1.03 (pindah datang)');
    await page.locator('select[name="jenis_permohonan"]').selectOption('SURAT KETERANGAN KEPENDUDUKAN');
    await page.locator('select[name="alasan_pindah"]').selectOption('PEKERJAAN');
    await page.locator('select[name="klasifikasi_pindah"]').selectOption('ANTAR PROVINSI');
    await page.getByRole('textbox', { name: 'Alamat Tujuan' }).fill('alamat tujuan');
    await page.getByRole('textbox', { name: 'RT Tujuan' }).fill('01');
    await page.getByRole('textbox', { name: 'RW Tujuan' }).fill('02');
    await page.getByRole('textbox', { name: 'Dusun Tujuan' }).fill('dusun');
    await page.getByRole('textbox', { name: 'Desa atau Kelurahan Tujuan' }).fill('desa');
    await page.getByRole('textbox', { name: 'Kecamatan Tujuan' }).fill('kecamatan');
    await page.getByRole('textbox', { name: 'Kabupaten Tujuan' }).fill('kabupaten');
    await page.getByRole('textbox', { name: 'Provinsi Tujuan' }).fill('provinsi');
    await page.getByRole('textbox', { name: 'Kode Pos Tujuan' }).fill('08122');
    await page.getByRole('textbox', { name: 'Telepon Tujuan' }).fill('081356297444');
    await page.locator('select[name="jenis_kepindahan"]').selectOption('KEP. KELUARGA DAN SELURUH ANGG. KELUARGA');
    await page.locator('select[name="status_kk_bagi_yang_tidak_pindah"]').selectOption('TIDAK ADA ANGG. KELUARGA YANG DITINGGAL');
    await page.locator('select[name="status_kk_bagi_yang_pindah"]').selectOption('NOMOR KK TETAP');
    await page.getByRole('textbox', { name: 'Tanggal Pindah' }).click();
    await page.getByRole('cell', { name: '25', exact: true }).click();
    await page.getByRole('textbox', { name: 'Keterangan' }).fill('keterangan');
    await page.getByRole('textbox', { name: 'Jumlah Pengikut' }).click();
    await page.getByRole('textbox', { name: 'Jumlah Pengikut' }).fill('3');
    await page.getByRole('button', { name: ' Lanjut' }).click();

    await page.getByRole('link', { name: 'F-1.03', exact: true }).click();
    await expect(page.locator('#mce_24_ifr').contentFrame().getByRole('row', { name: '10. Anggota Keluarga Yang Tidak Pindah' }).getByRole('cell').nth(2)).toBeVisible();
    await expect(page.locator('#mce_24_ifr').contentFrame().getByRole('row', { name: '11. Anggota Keluarga Yang Pindah' }).getByRole('cell').nth(2)).toBeVisible();
  });
});