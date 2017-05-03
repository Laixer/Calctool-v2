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
define('APP_NAME',      'CalculatieTool.com');
define('APP_ENV',       'production');
define('APP_DEBUG',     false);
define('APP_TIMEZONE',  'Europe/Amsterdam');
define('APP_VERSION',   'stable');
define('APP_URL',       'http://localhost');
define('APP_LOCALE',    'nl');
define('APP_LOG_LEVEL', 'error');

/* Businessline configuration */
// define('APP_LOGO',      '/images/logo.png');
// define('APP_LOGO_WIDTH',240);
define('APP_LOGO',        'https://www.workday.com/content/dam/web/en-us/images/logos/customers/logo_Visa_450x225.png/_jcr_content/renditions/cq5dam.logomd.300.150.png');
define('APP_LOGO_WIDTH',  110);
define('APP_STYLESHEET',  '/css/darkgreen.css');
define('APP_EMAIL',       ['info@calculatietool.com']);
define('ADMIN_EMAIL',     ['y.dewid@calculatietool.com']);