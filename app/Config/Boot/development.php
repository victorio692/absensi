<?php

/*
 |--------------------------------------------------------------------------
 | OUTPUT BUFFERING & SESSION SETTINGS
 |--------------------------------------------------------------------------
 | Atur output buffering SEBELUM apapun lainnya untuk mencegah header
 | telah terkirim saat session diinisialisasi
 */
if (ob_get_level() === 0) {
    ob_start();
}

/*
 |--------------------------------------------------------------------------
 | SESSION SETTINGS
 |--------------------------------------------------------------------------
 | Atur session settings sebelum session dimulai
 */
ini_set('session.use_strict_mode', '1');
ini_set('session.use_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.sid_length', '32');
ini_set('session.sid_bits_per_character', '5');

/*
 |--------------------------------------------------------------------------
 | ERROR DISPLAY
 |--------------------------------------------------------------------------
 | Di development, kita ingin menampilkan error sebanyak mungkin untuk
 | membantu memastikan mereka tidak masuk ke production.
 |
 | Jika Anda set 'display_errors' ke '1', CI4 akan menampilkan error
 | detail report.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 |--------------------------------------------------------------------------
 | DEBUG BACKTRACES
 |--------------------------------------------------------------------------
 | If true, this constant will tell the error screens to display debug
 | backtraces along with the other error information. If you would
 | prefer to not see this, set this value to false.
 */
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);

/*
 |--------------------------------------------------------------------------
 | DEBUG MODE
 |--------------------------------------------------------------------------
 | Debug mode is an experimental flag that can allow changes throughout
 | the system. This will control whether Kint is loaded, and a few other
 | items. It can always be used within your own application too.
 */
defined('CI_DEBUG') || define('CI_DEBUG', true);
