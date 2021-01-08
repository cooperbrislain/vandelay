<?php
require_once('includes/opts.php');
require_once('includes/data.php');

/*
<div class="container">
    <h2>Input</h2>
    <? render_fields($v_obj); ?>
</div>

<div class="container">
    <h2>Translation</h2>
    <? render_fields($t_obj); ?>
</div>

<div class="container">
    <h2>Output</h2>
    <? render_fields($o_obj); ?>
</div>
*/ // TODO: figure out where to put this or remove it.

if ($opts['domino'] == 1) {
    status("IMPORTING {$opts['start']} RECORDS BEGINNING AT {$opts['limit']}");
    if (!$opts['start']) status("START NOT DEFINED");
    else if (!$opts['limit']) status("LIMIT NOT DEFINED");
    else {
        $start = $opts['start'];
        $limit = $opts['limit'];
        $count = 0;
        $q = <<< QUERY
            SELECT v_id, status FROM 
                (SELECT venues.v_id, v_import.status 
                FROM venues LEFT JOIN v_import ON venues.v_id = v_import.v_id ) 
                    AS sub 
            WHERE sub.status IS NULL OR 0
                AND v_id >= {$start}
            LIMIT {$limit}
QUERY;
        $res = doq($q);
        while ($row = mysqli_fetch_assoc($res)) {
            $v_obj = import_venue($row['v_id']);
            if (!$v_obj) continue;
            if (!$v_obj->v_id) continue;
            print_r($v_obj);
            if ($v_obj->status) continue;
            $t_obj = translate_venue($v_obj);
            $o_obj = construct_post($t_obj);
            status("INSERTING {$v_obj->v_id}");
            if (insert_post($o_obj, $v_obj->v_id)) {
                $count++;
                status("{$count}/{$limit} POSTS INSERTED");
            }
        }
    }
}