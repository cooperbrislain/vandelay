<?php

//require_once ("../wp-config.php");
require_once("vendor/autoload.php");

use Dotenv\Dotenv;

error_reporting(E_ALL & ~E_NOTICE);

define( 'WP_DEBUG', false );
define('DIR_VENDOR', __DIR__.'/vendor/');

set_include_path(get_include_path() .
    PATH_SEPARATOR . '/vendor' .
    PATH_SEPARATOR . '..' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com/vandelay' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com/vandelay/vendor'
);

ini_set('html_errors', 1);
ini_set('display_errors', 1);;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$source = mysqli_connect(
    $_ENV['DB_HOST_SOURCE'],
    $_ENV['DB_USER_SOURCE'],
    $_ENV['DB_PASSWORD_SOURCE'],
    $_ENV['DB_NAME_SOURCE']);
$target = mysqli_connect(
    $_ENV['DB_HOST_TARGET'],
    $_ENV['DB_USER_TARGET'],
    $_ENV['DB_PASSWORD_TARGET'],
    $_ENV['DB_NAME_TARGET']);