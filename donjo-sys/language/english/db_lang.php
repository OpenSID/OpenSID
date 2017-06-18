<?php

$lang['db_invalid_connection_str'] = 'Unable to determine the database settings based on the connection string you submitted.';
$lang['db_unable_to_connect'] = '<h3>Ada kesalahan dalam pemasangan OpenSID</h3>Aplikasi tidak bisa terhubung ke database.<br />Silakan copy/paste folder desa-contoh sebagai folder desa dan periksa pada berkas di desa/config/database.php. Pastikan pada bagian "Data Konfigurasi MySQL yang disesuaikan" terisi dengan benar.<p></p><p>Untuk mengatasi kendala ini anda bisa membaca panduan/petunjuk di <a href="http://www.opensid.info/dokumentasi/4-panduan-install-opensid.html" target="_blank">tautan ini</a> atau di <a href="https://github.com/eddieridwan/OpenSID/wiki/Panduan-Install-OpenSID" target="_blank">tautan ini</a></p>';
$lang['db_unable_to_select'] = 'Unable to Tabel';
$lang['db_unable_to_create'] = 'Unable to create the specified database: %s';
$lang['db_invalid_query'] = 'The query you submitted is not valid.';
$lang['db_must_set_table'] = 'You must set the database table to be used with your query.';
$lang['db_must_use_set'] = 'You must use the "set" method to update an entry.';
$lang['db_must_use_index'] = 'You must specify an index to match on for batch updates.';
$lang['db_batch_missing_index'] = 'One or more rows submitted for batch updating is missing the specified index.';
$lang['db_must_use_where'] = 'Updates are not allowed unless they contain a "where" clause.';
$lang['db_del_must_use_where'] = 'Deletes are not allowed unless they contain a "where" or "like" clause.';
$lang['db_field_param_missing'] = 'To fetch fields requires the name of the table as a parameter.';
$lang['db_unsupported_function'] = 'This feature is not available for the database you are using.';
$lang['db_transaction_failure'] = 'Transaction failure: Rollback performed.';
$lang['db_unable_to_drop'] = 'Unable to drop the specified database.';
$lang['db_unsuported_feature'] = 'Unsupported feature of the database platform you are using.';
$lang['db_unsuported_compression'] = 'The file compression format you chose is not supported by your server.';
$lang['db_filepath_error'] = 'Unable to write data to the file path you have submitted.';
$lang['db_invalid_cache_path'] = 'The cache path you submitted is not valid or writable.';
$lang['db_table_name_required'] = 'A table name is required for that operation.';
$lang['db_column_name_required'] = 'A column name is required for that operation.';
$lang['db_column_definition_required'] = 'A column definition is required for that operation.';
$lang['db_unable_to_set_charset'] = 'Unable to set client connection character set: %s';
$lang['db_error_heading'] = 'A Database Error Occurred';

/* End of file db_lang.php */
/* Location: ./system/language/english/db_lang.php */