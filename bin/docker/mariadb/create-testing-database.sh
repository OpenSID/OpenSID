#!/usr/bin/env bash

DB_NAME="${MYSQL_DATABASE:-opensid-laravel-test}"
ROOT_PW="${MYSQL_ROOT_PASSWORD:-password}"

# Create the testing database (matches compose.yml DB_DATABASE)
/usr/bin/mariadb --user=root --password="$ROOT_PW" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
EOSQL

# Grant the application user full access to it
if [ -n "$MYSQL_USER" ]; then
/usr/bin/mariadb --user=root --password="$ROOT_PW" <<-EOSQL
    GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '$MYSQL_USER'@'%';
    FLUSH PRIVILEGES;
EOSQL
fi

# Import the OpenSID schema if present
if [ -f /schema.sql ]; then
    /usr/bin/mariadb --user=root --password="$ROOT_PW" --default-character-set=utf8mb4 "$DB_NAME" < /schema.sql
fi
