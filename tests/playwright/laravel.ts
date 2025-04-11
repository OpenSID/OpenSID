import { APIRequestContext, request as playwrightRequest } from '@playwright/test';

export class Laravel {
  private static baseUrl: string = (process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000') + '/playwright';
  private static requestContext: APIRequestContext | null = null;
  private static initializing: Promise<void> | null = null;

  private static async ensureInitialized() {
    if (!Laravel.requestContext) {
      if (!Laravel.initializing) {
        Laravel.initializing = (async () => {
          Laravel.requestContext = await playwrightRequest.newContext();
        })();
      }
      await Laravel.initializing;
    }
  }

  private static async call<T = any>(endpoint: string, data: object = {}): Promise<T> {
    await Laravel.ensureInitialized();

    const url = Laravel.baseUrl + endpoint;
    const response = await Laravel.requestContext!.post(url, {
      data,
      headers: {
        'Accept': 'application/json',
      },
    });

    if (!response.ok()) {
      const errorText = await response.text();
      throw new Error(`Request failed with status ${response.status()}: ${errorText}`);
    }

    return await response.json();
  }

  static async artisan(command: string, parameters: string[] = []) {
    return await Laravel.call('/artisan', { command, parameters });
  }

  static async user() {
    return await Laravel.call('/user');
  }

  static async query(
    query: string,
    bindings: any[] | Record<string, any> = [],
    options: { connection?: string | null; unprepared?: boolean } = {}
  ) {
    const { connection = null, unprepared = false } = options;

    if (unprepared && Array.isArray(bindings) && bindings.length > 0) {
      throw new Error('Cannot use unprepared with bindings');
    }

    return await Laravel.call('/query', { query, bindings, connection, unprepared });
  }

  static async select(
    query: string,
    bindings: any[] | Record<string, any> = [],
    options: { connection?: string | null } = {}
  ) {
    const { connection = null } = options;
    return await Laravel.call('/select', { query, bindings, connection });
  }
}
