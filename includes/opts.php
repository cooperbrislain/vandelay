<?php

$common_opts = [
    'show_queries'=>0,
    'meta'      =>  0,
    'verbose'   =>  0,
    'debug'     =>  0,
];

$opts = [
    'v_id'  =>  0,
    'domino'=>  0,
    'start' =>  0,
    'limit' =>  10,
    'page'  =>  'dashboard',
];

if (isset($opts)) $opts = array_merge($common_opts, $opts);
else $opts = $common_opts;

foreach($opts as $k => $v) {
    if (isset($_GET[$k])) {
        debug("Setting {$k} to {$_GET[$k]}");
        $opts[$k] = $_GET[$k];
    }
}
