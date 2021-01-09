<?php
$uses = Array();
$keys = Array();
$types = Array();

try {
    $q = "SELECT * FROM v_uses";
    $res = doq($q, false);
    if(is_bool($res)) throw new Exception('failed to load uses');
} catch (Exception $e) {;
    debug($e);
}
if (!is_bool($res))
    while ($row = mysqli_fetch_assoc($res))
        $uses[$row['use_id']] = $row['use_name'];

try {
    $q = "SELECT * FROM v_types";
    $res = doq($q, false);
    if (is_bool($res)) throw new Exception('failed to load types');
} catch (Exception $e) {
    debug($e);
}
if (!is_bool($res))
    while($row = mysqli_fetch_assoc($res)) {
        $str = strtolower($row['v_type_name']);
        $str = preg_replace('/[ \'\/]+/','_', $str);
        $types[$row['v_type_id']] = $str;
    }


