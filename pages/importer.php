<?php
require_once('includes/opts.php');
require_once('includes/data.php');
debug('importer');

if(!empty($opts)){
    $v_obj = import_venue($opts['v_id']);
} else {
    exit;
}
$t_obj = translate_venue($v_obj);
$o_obj = construct_post($t_obj);
?>
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

<?php if ($opts['domino'] == 1) {
    insert_post($o_obj, $v_obj->v_id);
}