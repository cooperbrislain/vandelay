<?php
require_once('includes/opts.php');
require_once('includes/data.php');

$q = <<<QUERY
    SELECT v_import.status, COUNT(venues.v_id) AS count FROM venues 
    LEFT JOIN v_import ON venues.v_id = v_import.v_id 
    GROUP BY v_import.status
QUERY;
$res = doq($q);
$num_total = 0;
$num_imported = 0;
$num_remaining = 0;
$num_errors = 0;
while(mysqli_fetch_assoc($res)) {
    switch($res['status']) {
        case(1) :
            $num_imported = $res['count'];
        case(-1) :
            $num_errors = $res['count'];
        default:
            $num_remaining = $res['count'];
    }
    $num_total += $res['count'];
}
?>
<div class="container">
    <dl>
        <dt>Total Items</dt><dd>$num_total</dd>
        <dt>Items Imported</dt><dd>$num_imported</dd>
        <dt>Items Remaining</dt><dd>$num_remaining</dd>
        <dt>Errors</dt><dd>$num_errors</dd>
    </dl>
</div>