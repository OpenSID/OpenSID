import { test, expect } from '@playwright/test';
import path from 'path';
import fs from 'fs';
import os from 'os';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});


// ─── Helper: Buat file gambar valid (PNG 1x1 piksel) ────────────────────────
function createValidPng(): Buffer {
  // PNG 1x1 piksel merah - binary valid
  const PNG_1x1_RED = Buffer.from(
    '89504e470d0a1a0a0000000d49484452000000010000000108020000' +
    '0090wc3d000000000c4944415408d76360f8cfc00000000200' +
    '01e221bc330000000049454e44ae426082',
    'hex'
  );
  // Gunakan PNG minimal yang benar-benar valid
  return Buffer.from([
    0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a, // PNG signature
    0x00, 0x00, 0x00, 0x0d, 0x49, 0x48, 0x44, 0x52, // IHDR chunk length + type
    0x00, 0x00, 0x00, 0x01, 0x00, 0x00, 0x00, 0x01, // width=1, height=1
    0x08, 0x02, 0x00, 0x00, 0x00, 0x90, 0x77, 0x53, // bit depth, color type, etc
    0xde, 0x00, 0x00, 0x00, 0x0c, 0x49, 0x44, 0x41, // IDAT chunk
    0x54, 0x08, 0xd7, 0x63, 0xf8, 0xcf, 0xc0, 0x00, // compressed pixel data
    0x00, 0x00, 0x02, 0x00, 0x01, 0xe2, 0x21, 0xbc, // CRC
    0x33, 0x00, 0x00, 0x00, 0x00, 0x49, 0x45, 0x4e, // IEND chunk
    0x44, 0xae, 0x42, 0x60, 0x82,                   // IEND CRC
  ]);
}

// ─── Helper: Buat GIF valid (10x10 merah) ───────────────────────────────────
function createValidGif(): Buffer {
  const base64 = 'R0lGODlhCgAKAIABAP8AAP///yH5BAEAAAAALAAAAAAKAAoAAAIHhI+py+0fADs=';
  return Buffer.from(base64, 'base64');
}

// ─── Helper: Buat GIF dengan payload XSS di biner ───────────────────────────
function createMaliciousGif(): Buffer {
  const gifBytes = createValidGif();
  const payload  = Buffer.from("!<-- <img src=x onerror=alert('XSS_TEST')> -->");
  // Sisipkan payload sebelum byte terakhir (0x3B trailer)
  return Buffer.concat([
    gifBytes.slice(0, -1),
    payload,
    gifBytes.slice(-1),
  ]);
}

// ─── Helper: Simpan buffer ke file tmp ──────────────────────────────────────
function writeTempFile(filename: string, buffer: Buffer): string {
  const tmpPath = path.join(os.tmpdir(), filename);
  fs.writeFileSync(tmpPath, buffer);
  return tmpPath;
}

// ─── Helper: Inject file via DataTransfer (bypass accept + validation) ───────
async function injectFileViaConsole(page: any, filePath: string, filename: string) {
  const fileBuffer = fs.readFileSync(filePath);
  const base64     = fileBuffer.toString('base64');

  await page.evaluate(({ base64Data, fname }: { base64Data: string; fname: string }) => {
    // Decode base64 ke binary
    const byteCharacters = atob(base64Data);
    const byteArray      = new Uint8Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) {
      byteArray[i] = byteCharacters.charCodeAt(i);
    }

    const blob = new Blob([byteArray], { type: 'image/gif' });
    const file = new File([blob], fname, { type: 'image/gif' });

    const fileInput = document.querySelector('#file') as HTMLInputElement;
    if (fileInput) {
      fileInput.removeAttribute('accept'); // bypass client filter
      const dt = new DataTransfer();
      dt.items.add(file);
      fileInput.files = dt.files;

      const filePath = document.querySelector('#file_path') as HTMLInputElement;
      if (filePath) filePath.value = fname;
    }
  }, { base64Data: base64, fname: filename });
}

test.describe('Bug/error: Celah Keamanan Stored XSS via Metadata Gambar pada Modul Galeri #10917', () => {
  test('fix: perbaiki Celah Keamanan Stored XSS via Metadata Gambar pada Modul Galeri', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10917',
    },
  }, async ({ page }) => {
    await page.goto('gallery');

    const maliciousFile = writeTempFile('xss_payload.gif', createMaliciousGif());

    await page.goto('gallery/form/0');
    await expect(page.locator('input[name="nama"]')).toBeVisible();

    await page.fill('input[name="nama"]', 'Audit XSS Test');
    await page.selectOption('select[name="jenis"]', '1');

    // Inject file berbahaya via console (simulasi bypass client-side)
    await injectFileViaConsole(page, maliciousFile, 'xss_payload.gif');

    // Submit form
    await page.click('button[type="submit"].confirm');

    // Harus muncul pesan error — file ditolak
    await expect(page.locator('.alert-danger')).toBeVisible({ timeout: 5000 });
    const errorText = await page.locator('.alert-danger').innerText();
    expect(errorText).toContain('tidak dapat diterima');

    // Pastikan TIDAK ada alert XSS yang muncul
    let xssExecuted = false;
    page.on('dialog', dialog => {
      if (dialog.message().includes('XSS')) {
        xssExecuted = true;
      }
      dialog.dismiss();
    });

    expect(xssExecuted).toBe(false);

    fs.unlinkSync(maliciousFile);
  });
});
