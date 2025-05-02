<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
define('THEME_VERSION', 'v101.1');
define('THEME_TIMESTAMP', '20250101');
define('IS_PREMIUM', preg_match('/premium/', ambilVersi()));
define('IS_240302', cekVersiMaksimal(240302));
define('IS_240610', cekVersiMinimal(240610));
define('IS_240710', cekVersiMinimal(240710));
define('IS_240803', cekVersiMinimal(240803));
define('IS_240810', cekVersiMinimal(240810));
define('IS_241010', cekVersiMinimal(241010));
?>
