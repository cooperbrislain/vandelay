<?php

error_reporting(E_ALL);
ini_set('html_errors', 1);
ini_set('display_errors', 1);;
require_once("../wp-config.php");
$source = mysqli_connect("127.0.0.1", DB_USER, DB_PASSWORD, "migrated_from_old_site");
$target = mysqli_connect("127.0.0.1", DB_USER, DB_PASSWORD, DB_NAME);