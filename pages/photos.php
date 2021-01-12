<?php
require_once('includes/opts.php');
require_once('includes/data.php');

$source_baseurl = 'http://smallweddings.com/ven_img';

if ($opts['v_id']) {
    $venues = get_photos($opts['v_id']);
}

include "includes/list_venue_photos.php";
