<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------
// Load the core Kohana class
require SYSPATH . 'classes/kohana/core' . EXT;

if (is_file(APPPATH . 'classes/kohana' . EXT))
{
    // Application extends the core
    require APPPATH . 'classes/kohana' . EXT;
}
else
{
    // Load empty core extension
    require SYSPATH . 'classes/kohana' . EXT;
}

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('Asia/Singapore');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-gb');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
Kohana::$environment = ($_SERVER['SERVER_NAME'] === 'api.realtasia.com.sg') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;


/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init([
    'base_url' => '/',
    'index_file' => '',
    'profile'  => Kohana::$environment === Kohana::PRODUCTION,
    'caching' => Kohana::$environment === Kohana::PRODUCTION
]);
/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH . 'logs'), [], Log::DEBUG);

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules([
    'RT'         => MODPATH . 'RT', // Core RT classes
    'queue'      => MODPATH . 'queue', // Core RT classes
    'interact'   => MODPATH . 'interact', // Interaction Models
    'cache'      => MODPATH . 'cache', // Caching with multiple backends
    'mongodb'    => MODPATH . 'mongodb', // Database access
    'email'      => MODPATH . 'email', // Emailer
    'sms'        => MODPATH . 'sms', // SMSer
    'database'   => MODPATH . 'database',   // Database access
    'image'      => MODPATH . 'image',      // Image manipulation
    'purifier'   => MODPATH . 'purifier',        // Object Relationship Mapping
    'ticker'     => MODPATH . 'ticker',        // Object Relationship Mapping
	//'deploy'	   => MODPATH . 'deploy'       
]);

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach( Kohana::$environment === Kohana::PRODUCTION 
	? new Log_Mongo() 
	: new Log_File(APPPATH . 'logs')
	, array(), new Log_Mongo(), [], Log::DEBUG );
	
Route::set('singpost', 'singpost(/<action>(/<id>))')
        ->defaults([
            'controller' => 'singpost',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('user', 'user(/<action>(/<id>))', ['id' => '[^/,;?]++'])
        ->defaults([
            'controller' => 'user',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('post', 'post(/<action>(/<id>))')
        ->defaults([
            'controller' => 'post',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('listing', 'listing(/<action>(/<id>))')
        ->defaults([
            'controller' => 'listing',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('comment', 'comment(/<action>(/<id>))')
        ->defaults([
            'controller' => 'comment',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('page', 'page(/<action>(/<id>))')
        ->defaults([
            'controller' => 'page',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('media', 'media(/<action>(/<id>))')
        ->defaults([
            'controller' => 'media',
            'action'     => 'index',
            'id'         => null
        ]);

Route::set('auth', 'auth(/<action>(/<id>))')
        ->defaults([
            'controller' => 'auth',
            'action'     => 'index',
            'id'         => null
        ]);
Route::set('admin', 'admin(/<action>(/<id>))')
        ->defaults([
            'controller' => 'admin',
            'action'     => 'index',
            'id'         => null
        ]);
Route::set('avatar', 'avatar(/<id>(/<size>))')
        ->defaults([
            'controller' => 'avatar',
            'action'     => 'index',
            'id'         => null,
            'size'       => null
        ]);
/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('default', '(<controller>(/<action>(/<id>)))',["id" => "[a-zA-Z0-9_]+","action" => "[a-zA-Z]+"])
        ->defaults([
            'controller' => 'testpad',
            'action'     => 'index',
            'id'         => null
        ]);
Cookie::$salt = '123456789';