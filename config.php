<?php

use Dotenv\Dotenv;

set_include_path(get_include_path() .
    PATH_SEPARATOR . '/vendor' .
    PATH_SEPARATOR . '..' .
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com'.
    PATH_SEPARATOR . '/home/t04ucpbct3k8/smallweddings.com/vandelay'
);
echo get_include_path();
error_reporting(E_ALL);
ini_set('html_errors', 1);
ini_set('display_errors', 1);;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$source = mysqli_connect("127.0.0.1", $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], "migrated_from_old_site");
$target = mysqli_connect("127.0.0.1", $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);