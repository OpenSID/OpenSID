import { execSync } from 'child_process';
import path from 'path';
import fs from 'fs';

const root = path.resolve(__dirname, '../../../..');

/**
 * Sengaja TIDAK jatuh ke process.env.DB_DATABASE -- itu variabel APLIKASI,
 * bukan variabel Playwright. globalSetup men-DROP+re-provisi database ini,
 * jadi fixture test harus terisolasi terlepas dari environment ambient
 * (pelajaran yang sama dengan globalSetup.ts Premium).
 */
function testDatabase(): string {
    return process.env.PLAYWRIGHT_DB_DATABASE || 'opensid_playwright_umum';
}

function dbArgs(): string {
    const host = process.env.PLAYWRIGHT_DB_HOST || '127.0.0.1';
    const port = process.env.PLAYWRIGHT_DB_PORT || '3306';
    const user = process.env.PLAYWRIGHT_DB_USERNAME || 'root';
    const password = process.env.PLAYWRIGHT_DB_PASSWORD || '';
    const database = testDatabase();

    const passFlag = password ? `-p${password}` : '';
    return `-h ${host} -P ${port} -u ${user} ${passFlag} ${database}`;
}

export default async function globalSetup() {
    // Hanya provisi saat diminta eksplisit (lokal). Di CI, DB diprovisi pada
    // langkah workflow SEBELUM Playwright -- readiness webServer dicek sebelum
    // globalSetup, jadi DB harus sudah lengkap saat server start.
    const shouldReset = process.env.PLAYWRIGHT_RESET_DB === 'true';
    if (!shouldReset) {
        return;
    }

    const database = testDatabase();
    const dbDir = path.resolve(__dirname, '../../storage/database');
    const schemaFile = path.join(dbDir, 'schema-2701.sql');
    const seedFile = path.join(dbDir, 'seed-2701.sql');
    const desaZip = path.join(dbDir, 'desa-2701.zip');

    const mysqlBin = process.env.PLAYWRIGHT_MYSQL_BIN || 'mysql';

    console.log(`\n[globalSetup] Membuat ulang database ${database}...`);
    execSync(`"${mysqlBin}" -h ${process.env.PLAYWRIGHT_DB_HOST || '127.0.0.1'} -u ${process.env.PLAYWRIGHT_DB_USERNAME || 'root'} ${process.env.PLAYWRIGHT_DB_PASSWORD ? `-p${process.env.PLAYWRIGHT_DB_PASSWORD}` : ''} -e "DROP DATABASE IF EXISTS ${database}; CREATE DATABASE ${database} CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"`, { stdio: 'inherit' });

    console.log('[globalSetup] Importing schema-2701.sql...');
    execSync(`"${mysqlBin}" ${dbArgs()} < "${schemaFile}"`, { stdio: 'inherit', cwd: root });

    console.log('[globalSetup] Importing seed-2701.sql...');
    execSync(`"${mysqlBin}" ${dbArgs()} < "${seedFile}"`, { stdio: 'inherit', cwd: root });

    console.log('[globalSetup] Extracting desa-2701.zip...');
    // Arsip sudah berprefiks `desa/`, ekstrak ke ROOT repo (cwd) agar
    // menghasilkan `desa/...`, bukan `desa/desa/...`.
    execSync(`unzip -o "${desaZip}" -d .`, { stdio: 'inherit', cwd: root });

    const storageDirs = [
        'storage/framework/views',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/logs',
        'bootstrap/cache',
    ];
    for (const dir of storageDirs) {
        fs.mkdirSync(path.join(root, dir), { recursive: true });
    }

    // File cache menyimpan throttle rate-limiter (login) + query cache lintas
    // proses `php -S` -- tanpa dibersihkan, sisa hit dari run sebelumnya bisa
    // membuat smoke-boot's login test terkena "Terlalu Banyak Permintaan."
    // walau DB baru saja direset.
    for (const sub of ['data', 'sessions']) {
        const dir = path.join(root, 'storage/framework/cache', sub);
        if (fs.existsSync(dir)) {
            fs.rmSync(dir, { recursive: true, force: true });
        }
    }

    console.log('[globalSetup] Done.\n');
}
