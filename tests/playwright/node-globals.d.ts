declare const __dirname: string;

declare const process: {
  env: Record<string, string | undefined>;
};

declare module 'path' {
  const path: {
    resolve: (...paths: string[]) => string;
  };

  export = path;
}
