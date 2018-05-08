<?php
/*
 * Ini hanya untuk mengalihkan  ke index php yang baru. nanti boleh dihapus.
 */
header('Location: '. trim(dirname($_SERVER['SCRIPT_NAME']), '/') .'/web/index.php'. $_SERVER['PATH_INFO']);
