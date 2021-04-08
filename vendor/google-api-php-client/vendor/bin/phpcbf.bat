@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../squizlabs/php_codesniffer/scripts/phpcbf
php "%BIN_TARGET%" %*
