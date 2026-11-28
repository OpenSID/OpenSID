#!/bin/bash
set -e

# Ensure .env exists (gitignored, so a fresh Codespace clone has none). This
# runs as the unprivileged "sail" user; start-container may not have created it
# yet, so create it here to keep this script self-sufficient.
if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# In a Codespace the proxy forwards port 80 to a URL like:
#   https://<CODESPACE_NAME>-80.<GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN>
# Write it into .env so the PHP worker (which reads .env via Dotenv, unlike
# containerEnv/compose env that don't reach php artisan serve) gets the right URL.
if [ "${CODESPACES}" = "true" ] && [ -n "${CODESPACE_NAME}" ] && [ -n "${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}" ]; then
    APP_URL="https://${CODESPACE_NAME}-80.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"
    sed -i '/^APP_ENV=/d' /var/www/html/.env
    sed -i '/^APP_URL=/d' /var/www/html/.env
    sed -i '/^ASSET_URL=/d' /var/www/html/.env
    sed -i '/^TRUSTED_PROXIES=/d' /var/www/html/.env
    printf 'APP_ENV=testing\n' >> /var/www/html/.env
    printf 'TRUSTED_PROXIES=%s\n' '*' >> /var/www/html/.env
    printf 'APP_URL=%s\n' "$APP_URL" >> /var/www/html/.env
    printf 'ASSET_URL=%s/assets\n' "$APP_URL" >> /var/www/html/.env
fi

# Restart the worker so it picks up the updated .env. The worker (supervisord)
# may not be ready yet at postCreate time, so wait until it is reachable before
# restarting; otherwise the .env is written but the running worker keeps the old
# (localhost) config until something else triggers a reload.
for i in $(seq 1 30); do
    if supervisorctl status >/dev/null 2>&1; then
        supervisorctl restart all
        break
    fi
    sleep 1
done
