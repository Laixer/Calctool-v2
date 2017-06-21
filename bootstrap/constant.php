<?php

/*
|--------------------------------------------------------------------------
| Register Constant Definitions
|--------------------------------------------------------------------------
|
| This file provides a single point for global definitions and will 
| decouple implementation from configuration. This list must include
| everything that should be marked as static data.
|
*/

/* Default values for environment based config */
define('APP_NAME',      'Dynq');
define('APP_ENV',       'production');
define('APP_DEBUG',     false);
define('APP_TIMEZONE',  'Europe/Amsterdam');
define('APP_VERSION',   'stable');
define('APP_URL',       'http://localhost');
define('APP_LOCALE',    'en');
define('APP_LOG_LEVEL', 'error');

/* Businessline configuration */
// define('APP_LOGO',      '/images/logo.png');
// define('APP_LOGO_WIDTH',240);
define('APP_LOGO',        '/images/dynq2.png');
define('APP_LOGO_WIDTH',  160);
define('APP_STYLESHEET',  '/css/theme/brown.css');
// define('APP_STYLESHEET',  '/css/theme/deepgreen.css');
define('APP_EMAIL',       ['info@calculatietool.com']);
define('ADMIN_EMAIL',     ['y.dewid@calculatietool.com']);

/* Localization */
define('LOCALE_CURRENCY',    '&#3647;');
define('LOCALE_SEPARATOR',   '.');
define('LOCALE_DECIMAL',     ',');
define('LOCALE_DECIMALS',    3);
define('LOCALE_DATE',        'yyyy-mm-dd');
