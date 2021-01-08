<?php
require_once('includes/opts.php');
require_once('includes/data.php');

$q = <<<QUERY
    SELECT COUNT(venues.v_id) AS count FROM venues 
    LEFT JOIN v_import ON venues.v_id == v_import.v_id 
    GROUP BY v_import.status
QUERY;
$res = doq($q);

?>
<div class="container">
    <dl>
        <dt>Total Items</dt><dd></dd>
        <dt>Items Imported</dt><dd></dd>
        <dt>Items Remaining</dt><dd></dd>
        <dt>Errors</dt><dd></dd>
    </dl>
</div>