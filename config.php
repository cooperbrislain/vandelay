<?php
require_once ("../wp-config.php");
require_once("vendor/autoload.php");

use Dotenv\Dotenv;

define( 'WP_DEBUG', false );

define('DIR_VENDOR', __DIR__.'/vendor/');

set_include_path(get_include_path() .
    PATH_SEPARATOR . '/vendor' .
    PATH_SEPARATOR . '..' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com/vandelay' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com/vandelay/vendor'
);

error_reporting(E_ALL);
ini_set('html_errors', 1);
ini_set('display_errors', 1);;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$source = mysqli_connect("127.0.0.1", $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], "migrated_from_old_site");
$target = mysqli_connect("127.0.0.1", $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);