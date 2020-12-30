<?php
$opts = [
    'start'=>0,
    'limit'=>5
];
require_once('includes/config.php');
require_once('includes/functions.php');
require_once('includes/opts.php');
require_once('includes/data.php');
?>
<html lang="en">
<? require('includes/head.php'); ?>
<body>
<? require_once('includes/header.php'); ?>
<main>
<? switch($_GET['page']) {
    case 'import': include('pages/importer.php'); break;
    case 'export': include('pages/exporter.php'); break;
    case 'venue': include('pages/venue.php'); break;
    case 'debug': include('pages/debug.php'); break;
    default: break;
} ?>
</main>
<? require_once('includes/footer.php'); ?>
</body>
</html>