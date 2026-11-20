import mysql from 'mysql2/promise';
import { execSync } from 'child_process';
import path from 'path';

const root = path.resolve(__dirname, '../../..');

function connectionConfig(): mysql.ConnectionOptions {
    return {
        host:     process.env.PLAYWRIGHT_DB_HOST     || process.env.DB_HOST     || '127.0.0.1',
        port:     parseInt(process.env.PLAYWRIGHT_DB_PORT || process.env.DB_PORT || '3306'),
        database: process.env.PLAYWRIGHT_DB_DATABASE || process.env.DB_DATABASE || 'opensid_playwright',
        user:     process.env.PLAYWRIGHT_DB_USERNAME || process.env.DB_USERNAME || 'root',
        password: process.env.PLAYWRIGHT_DB_PASSWORD || process.env.DB_PASSWORD || '',
        multipleStatements: true,
    };
}

let _connection: mysql.Connection | null = null;

async function getConnection(): Promise<mysql.Connection> {
    if (!_connection) {
        _connection = await mysql.createConnection(connectionConfig());
    }
    return _connection;
}

export class Laravel {
    static async artisan(command: string, parameters: Record<string, unknown> = {}): Promise<string> {
        if (parameters && Object.keys(parameters).length > 0) {
            throw new Error('artisan() parameters not yet supported via execSync — pass them as part of the command string');
        }
        const database = process.env.PLAYWRIGHT_DB_DATABASE || process.env.DB_DATABASE || 'opensid_playwright';
        const output = execSync(`php artisan ${command}`, {
            cwd: root,
            env: { ...process.env, OPENSID_DB_DATABASE: database },
        });
        return output.toString();
    }

    static async query(
        query: string,
        bindings: unknown[] | Record<string, unknown> = [],
        options: { connection?: string | null; unprepared?: boolean } = {}
    ): Promise<unknown> {
        if (options.unprepared && Array.isArray(bindings) && bindings.length > 0) {
            throw new Error('Cannot use unprepared with bindings');
        }
        const conn = await getConnection();
        if (options.unprepared) {
            const [result] = await conn.query(query);
            return result;
        }
        const params = Array.isArray(bindings) ? bindings : Object.values(bindings);
        const [result] = await conn.execute(query, params);
        return result;
    }

    static async select(
        query: string,
        bindings: unknown[] | Record<string, unknown> = [],
        // eslint-disable-next-line @typescript-eslint/no-unused-vars
        options: { connection?: string | null } = {}
    ): Promise<unknown[]> {
        const conn = await getConnection();
        const params = Array.isArray(bindings) ? bindings : Object.values(bindings);
        const [rows] = await conn.execute(query, params);
        return rows as unknown[];
    }

    static async user(username = 'admin'): Promise<Record<string, unknown>> {
        const conn = await getConnection();
        const [rows] = await conn.execute(
            'SELECT * FROM user WHERE username = ? LIMIT 1',
            [username]
        );
        const users = rows as Record<string, unknown>[];
        if (users.length === 0) {
            throw new Error(`User '${username}' not found in test database`);
        }
        return users[0];
    }
}
