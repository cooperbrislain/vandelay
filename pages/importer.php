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
        $i = $opts['start'];
        $limit = $opts['limit'];
        $count = 0;
        while ($count < $limit) {
            if (!$v_obj = import_venue($i)) continue;
            if ($v_obj->status) continue;
            $t_obj = translate_venue($v_obj);
            $o_obj = construct_post($t_obj);
            if (insert_post($o_obj, $v_obj->v_id)) {
                $count++;
                status("{$count} POSTS INSERTED");
            }
            $i++;
        }
    }
}