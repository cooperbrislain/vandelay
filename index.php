<?php
$opts = [
    'start' =>  0,
    'limit' =>  5,
];
require_once('config.php');
require_once('includes/functions.php');
require_once('includes/opts.php');
require_once('includes/data.php');
require_once('../wp-includes/post.php');
?>
<html lang="en">
<? require_once('includes/head.php'); ?>
<body>
<? require_once('includes/header.php'); ?>
<main>
<? switch($opts['page']) {
    case 'import':  include('pages/importer.php'); break;
    case 'export':  include('pages/exporter.php'); break;
    case 'venue':   include('pages/venue.php'); break;
    case 'debug':   include('pages/debug.php'); break;
    default:
    case 'dashboard': include('pages/dashboard.php'); break;
    case 'photos': include('pages/photos.php'); break;
} ?>
</main>
<? require_once('includes/footer.php'); ?>
</body>
</html>