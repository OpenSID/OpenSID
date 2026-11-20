import { execSync } from 'child_process';
import path from 'path';
import fs from 'fs';

const root = path.resolve(__dirname, '../..');

function dbArgs(): string {
    const host = process.env.PLAYWRIGHT_DB_HOST || process.env.DB_HOST || '127.0.0.1';
    const port = process.env.PLAYWRIGHT_DB_PORT || process.env.DB_PORT || '3306';
    const user = process.env.PLAYWRIGHT_DB_USERNAME || process.env.DB_USERNAME || 'root';
    const password = process.env.PLAYWRIGHT_DB_PASSWORD || process.env.DB_PASSWORD || '';
    const database = process.env.PLAYWRIGHT_DB_DATABASE || process.env.DB_DATABASE || 'opensid_playwright';

    const passFlag = password ? `-p${password}` : '';
    return `-h ${host} -P ${port} -u ${user} ${passFlag} ${database}`;
}

export default async function globalSetup() {
    // Hanya provisi saat diminta eksplisit (lokal). Di CI, DB diprovisi pada
    // langkah workflow SEBELUM Playwright — sebab readiness webServer (`/siteman`)
    // dicek sebelum globalSetup, sehingga DB harus sudah lengkap saat server start.
    const shouldReset = process.env.PLAYWRIGHT_RESET_DB === 'true';
    if (!shouldReset) {
        return;
    }

    const database = process.env.PLAYWRIGHT_DB_DATABASE || process.env.DB_DATABASE || 'opensid_playwright';
    const schemaFile = path.resolve(__dirname, 'storage/database/schema.sql');
    const seedFile = path.resolve(__dirname, 'storage/database/seed.sql');
    const desaZip = path.resolve(__dirname, 'storage/database/desa.zip');

    const mysqlBin = process.env.PLAYWRIGHT_MYSQL_BIN || 'mysql';

    console.log(`\n[globalSetup] Importing ${database} from schema.sql...`);
    execSync(`"${mysqlBin}" ${dbArgs()} < "${schemaFile}"`, { stdio: 'inherit', cwd: root });

    if (fs.existsSync(seedFile)) {
        console.log('[globalSetup] Importing seed data from seed.sql...');
        execSync(`"${mysqlBin}" ${dbArgs()} < "${seedFile}"`, { stdio: 'inherit', cwd: root });
    }

    if (fs.existsSync(desaZip)) {
        console.log('[globalSetup] Extracting desa.zip...');
        // Arsip sudah berprefiks `desa/`, jadi ekstrak ke ROOT repo (cwd) agar menghasilkan
        // `desa/...`, bukan `desa/desa/...`.
        execSync(`unzip -o "${desaZip}" -d .`, { stdio: 'inherit', cwd: root });
    }

    console.log('[globalSetup] Running opensid:setup...');
    execSync('php artisan opensid:setup', {
        stdio: 'inherit',
        cwd: root,
        env: { ...process.env, OPENSID_DB_DATABASE: database },
    });

    // Clear Laravel file cache — QueryCacheable uses forever-cache and file driver
    // doesn't support tag-based invalidation, so stale query results survive DB resets.
    const cacheDir = path.resolve(root, 'storage/framework/cache/data');
    if (fs.existsSync(cacheDir)) {
        console.log('[globalSetup] Clearing Laravel file cache...');
        execSync(`find "${cacheDir}" -type f -not -name ".gitkeep" -not -name "index.html" -not -name ".htaccess" -delete`);
    }

    console.log('[globalSetup] Done.\n');
}
