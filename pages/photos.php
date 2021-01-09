<?php
require_once('includes/opts.php');
require_once('includes/data.php');

$q = <<<QUERY
    SELECT * FROM venue_photos
    INNER JOIN v_import ON venue_photos.v_id = v_import.v_id
    GROUP BY venue_photos.v_id
QUERY;
$res = doq($q);
while ($row = mysqli_fetch_assoc($res)) {
    status(print_r($row, true));
}