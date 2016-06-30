<?php

/**
  Securimage sample config file (rename to config.inc.php to activate)

  Place your custom configuration in this file to make settings global so they
  are applied to the captcha image, audio playback, and validation.

  Using this file is optional but makes settings managing settings easier,
  especially when upgrading to a new version.

  When a new Securimage object is created, if config.inc.php is found in the
  Securimage directory, these settings will be applied *before* any settings
  passed to the constructor (so options passed in will override these).

  This file is especially useful if you use a custom database or session
  configuration and is easier than modifying securimage.php directly.
  Any class property from securimage.php can be used here.
*/

return array(
    /**** CAPTCHA Appearance Options ****/

    'image_width'      => 215,       // width of captcha image in pixels
    'image_height'     => 80,        // height of captcha image in pixels
    'code_length'      => 6,         // # of characters for captcha code
    'image_bg_color'   => '#ffffff', // hex color for image background
    'text_color'       => '#707070', // hex color for captcha text
    'line_color'       => '#707070', // hex color for lines over text
    'num_lines'        => 5,         // # of lines to draw over text

    'wordlist_file'    => 'words/words.txt', // text file for word captcha
    'use_wordlist'     => false,             // true to use word list
    'wordlist_file_encoding' => null,        // character encoding of word file if other than ASCII (e.g. UTF-8, GB2312)

    // example UTF-8 charset (TTF file must support symbols being used
    // 'charset'          => "абвгдeжзийклмнопрстуфхцчшщъьюяАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЮЯ",

    'ttf_file'         => './AHGBold.ttf',   // TTF file for captcha text

    /**** Code Storage & Database Options ****/

    // true if you *DO NOT* want to use PHP sessions at all, false to use PHP sessions
    'no_session'       => false,

    // the PHP session name to use (null for default PHP session name)
    // do not change unless you know what you are doing
    'session_name'     => null,

    // change to true to store codes in a database
    'use_database'     => false,

    // database engine to use for storing codes.  must have the PDO extension loaded
    // Values choices are:
    // Securimage::SI_DRIVER_MYSQL, Securimage::SI_DRIVER_SQLITE3, Securimage::SI_DRIVER_PGSQL
    'database_driver'  => Securimage::SI_DRIVER_MYSQL,

    'database_host'    => 'localhost',     // database server host to connect to
    'database_user'    => 'root',          // database user to connect as
    'database_pass'    => '',              // database user password
    'database_name'    => 'securimage',    // name of database to select (you must create this first or use an existing database)
    'database_table'   => 'captcha_codes', // database table for storing codes, will be created automatically

    // Securimage will automatically create the database table if it is not found
    // change to true for performance reasons once database table is up and running
    'skip_table_check' => false,

);
