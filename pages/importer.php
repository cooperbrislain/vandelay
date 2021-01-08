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
    if ($opts['start'] && $opts['limit']) {
        $i=$opts['start'];
        $limit = $opts['limit'];
        $count = 0;
        while ($count < $limit) {
            if (!$v_obj = import_venue($i)) continue;
            $t_obj = translate_venue($v_obj);
            $o_obj = construct_post($t_obj);
            if (insert_post($o_obj, $v_obj->v_id))
                $count++;
            $i++;
        }
    }
}