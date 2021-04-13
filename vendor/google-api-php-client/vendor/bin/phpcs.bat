@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../squizlabs/php_codesniffer/scripts/phpcs
php "%BIN_TARGET%" %*
