<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


$databases['default']['default'] = array (
  'database' => 'live_paycheck_exchange',
  'username' => 'payxchg-live',
  'password' => 'kbncU6aVy8ZwE9EPAA2qfjVmZJzYg8Ax',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);
//$settings['install_profile'] = 'standard';

/*
$client_data = [
  'live' => [
    'hostnames' => [
      "paycheckexchange.com", // << set preferred hostname first
      "www.paycheckexchange.com",
      "live-paycheck-exchange.clientsites.achieveagency.com"
    ],
    'redis' => [
      'host' => 'localhost',
      'prefix' => 'live-redis'
    ],
    'database' => [
  	  'database' => 'live_paycheck_exchange',
  	  'username' => 'payxchg-live',
  	  'password' => 'kbncU6aVy8ZwE9EPAA2qfjVmZJzYg8Ax',
  	  'host' => 'localhost'
    ],
    
	
	
	'twig' => [
      'debug' => FALSE
    ],
    'security' => [
      'salt' => 'VA95ltrQYGthM9A4H1McviaaGEZNzA0-6pyYNk5X9TLj3QWQKboEU_a7H-pztlM8_sU2NKmIEg'
    ],
    'debug' => FALSE
  ],
  'dev' => [
    'hostnames' => [
      "dev-paycheck-exchange.clientsites.achieveagency.com"
    ],
    'redis' => [
      'host' => 'localhost',
      'prefix' => 'dev-redis'
    ],
    'database' => [
  	  'database' => 'dev_paycheck_exchange',
  	  'username' => 'payxchg-dev',
  	  'password' => 'fjDv6jVaqBJpBJ9Kpryhnf38upydbEd8',
  	  'host' => 'localhost',
    ],
    'twig' => [
      'debug' => TRUE
    ],
    'security' => [
      'salt' => 'VA95ltrQYGthM9A4H1McviaaGEZNzA0-6pyYNk5X9TLj3QWQKboEU_a7H-pztlM8_sU2NKmIEg'
    ],
    'debug' => TRUE
  ]
];

*/
/************************************* ATTN: DEVS **************************************/
/************************* OVERRIDES GO IN SETTINGS.LOCAL.PHP **************************/
/********************************* DO NOT MODIFY BELOW *********************************/

$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

$init = function($env) use ($client_data, &$settings, &$databases, &$config_directories) {
  $config_directories = [ CONFIG_SYNC_DIRECTORY => dirname(DRUPAL_ROOT) . '/config' ];
  $settings['install_profile'] = 'standard';
  $settings['hash_salt'] = $client_data[$env]['security']['salt'];

  // configure database
  $databases['default']['default'] = array(
    'driver' => 'mysql',
    'database' => $client_data[$env]['database']['database'],
    'username' => $client_data[$env]['database']['username'],
    'password' => $client_data[$env]['database']['password'],
    'host' => $client_data[$env]['database']['host'],
    'pdo' => array(PDO::MYSQL_ATTR_COMPRESS => 1),
  );

  if ( in_array($env, ['dev', 'live']) ) {
    if ($env == 'live') {
      $host = $client_data[$env]['hostnames'][0];
      // if ($_SERVER['HTTP_HOST'] != $host || !isset($_SERVER['HTTP_X_SSL']) || $_SERVER['HTTP_X_SSL'] != 'ON' ) {
      //   header('HTTP/1.0 301 Moved Permanently');
      //   header("Location: https://{$host}{$_SERVER['REQUEST_URI']}");
      //   exit();
      // }
    }

    // configure redis
    $settings['redis.connection']['interface'] = 'PhpRedis';
    $settings['cache']['default'] = 'cache.backend.redis';
    $settings['redis.connection']['host'] = $client_data[$env]['redis']['host'];
    $settings['cache_prefix']['default'] = $client_data[$env]['redis']['prefix'];
  }

  // configure twig
  require_once DRUPAL_ROOT . '/modules/contrib/devel/kint/kint/Kint.class.php';
  Kint::$maxLevels = 5;
  $settings['twig_debug'] = $client_data[$env]['twig']['debug'];

  // configure client debug
  $settings['client_debug'] = $client_data[$env]['debug'];
};

if (isset($_SERVER['PANTHEON_ENVIRONMENT'])) {
  $init($_SERVER['PANTHEON_ENVIRONMENT']);
}
elseif (isset($_SERVER['HTTP_HOST'])) {
  foreach ($client_data as $env => $data) {
    foreach ($client_data[$env]['hostnames'] as $hostname) {
      if ($hostname == $_SERVER['HTTP_HOST']) {
        $init($env);
      }
    }
  }
}
$settings['container_yamls'][] = __DIR__ . '/services.yml';
ini_set('memory_limit', '1024M');
//$conf['cache_backends'][] = 'sites/all/modules/memcache/memcache.inc';
//$conf['cache_backends'][] = 'modules/memcache/memcache.inc';
//$conf['cache_default_class'] = 'MemCacheDrupal';
//$conf['memcache_servers'] = array('127.0.0.1:11211' => 'default');
//$conf['memcache_bins'] = array('cache' => 'default');
//$conf['cache_class_form'] = 'DrupalDatabaseCache';

// Set’s default cache storage as Memcache and excludes database connection for cache
$settings['cache']['default'] = 'cache.backend.memcache_storage';
// Set’s Memcache key prefix for your site and useful in working sites with same memcache as backend.
$settings['memcache_storage']['key_prefix'] = '';
// Set’s Memcache storage server’s.
$settings['memcache_storage']['memcached_servers'] =  ['127.0.0.1:11211' => 'default'];
// Enables to display total hits and misses
//$settings['memcache_storage']['debug'] = TRUE;
//$settings['update_free_access'] = TRUE;
//$settings['cache']['default'] = 'cache.backend.memcache_storage';
/****************************************************************************************************/
/*
if (file_exists('./modules/fast404/fast404.inc')) {
    include_once './modules/fast404/fast404.inc';
    fast404_preboot($settings);
  }
 $settings['fast404_path_check'] = FALSE;
 $settings['fast404_path_check'] = FALSE;
$settings['fast404_whitelist']  = array('index.php', 'rss.xml', 'install.php', 'cron.php', 'update.php', 'xmlrpc.php');
$conf['fast404_allow_anon_imagecache'] = TRUE;
$settings['fast404_string_whitelisting'] = array('cdn/farfuture');
$settings['fast404_string_whitelisting'] = array('/advagg_');
$settings['fast404_exts'] = '/^(?!robots).*\.(txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$settings['fast404_allow_anon_imagecache'] = TRUE;
$conf['fast_404_HTTP_status_method'] = 'FastCGI';
$conf['fast404_url_whitelisting'] = FALSE;
$settings['fast404_whitelist'] = array('index.php', 'rss.xml', 'install.php', 'cron.php', 'update.php', 'xmlrpc.php');
$settings['fast404_string_whitelisting'] = array('cdn/farfuture', '/advagg_');
$settings['fast404_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

*/

$settings['hash_salt'] = '_h2xUcbil_wai-pK9gcoQOa3Z3-VUzDD9u8lku4KXg2lyqokrJpflOBADHiPsfvkiaaLYOE0Qw';
