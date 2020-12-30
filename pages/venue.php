<?php
require_once('includes/opts.php');
require_once('includes/data.php'); // todo: probably won't need this

// todo: spin off into a function
//$in_photos = array();
//$q = "SELECT photo, cover FROM venue_photos WHERE v_id = {$opts['v_id']}";
//$res = doq($q);
//if(!is_bool($res))
//    while($row = mysqli_fetch_assoc($res))
//        $in_photos[] = $row['photo'];
//
//$arr = array();
//foreach(explode(',', $in_obj['types']) as $k) {
//    $arr[] = $types[$k];
//}
//$in_obj['types'] = $arr;

$out_post = [
    'id' => $in_obj['v_id'],
    'post_content' => $in_obj['descr'],
    'post_title' => $in_obj['name'],
    'post_name' => $in_obj['url'],
    'post_status' => 'draft',
    'post_excerpt' => $in_obj['descr'],
];

$out_meta = [
    'venue_name'        => $in_obj['name'],
    'venue_type'        => serialize($in_obj['types']),
    'venue_address'     => $in_obj['address'],
    'venue_city'        => $in_obj['city'],
    'venue_state'       => $in_obj['state'],
    'venue_zip'         => $in_obj['zip'],
    'venue_website'     => $in_obj['website'],
    'venue_capacity'    => intval($in_obj['capacity']),
    'venue_parking'     => intval($in_obj['parking_cap']),
    'venue_availability'=> $in_obj['avail'],
    'venue_views'       => trim($in_obj['has_views'], "\s, "),
    'venue_year'        => intval($in_obj['year_est']),
    'venue_use'         => serialize($in_obj['venue']),
    'venue_alcohol'     => serialize($in_obj['alcohol']),
    'venue_music'       => serialize($in_obj['music']),
    'venue_catering'    => serialize($in_obj['catering']),
    'venue_accom'       => serialize($in_obj['accom']),
    'venue_wheelchair'  => intval($in_obj['wheelchair']),
    'venue_wedding_pack'=> intval($in_obj['wed_pack_avail']),
    'venue_coordinator' => intval($in_obj['coord_avail']),
    'venue_minister'    => intval($in_obj['minister_avail']),
];

$out_photos = $in_photos;

$new_id = 999; // placeholder

?>
<h1>Venue Info</h1>
<div class="venue migration container">
    <div class="data in">
        <h3>Input</h3>
        <table id="#venue_detail">
            <thead><tr><td>Key</td><td>Value</td></tr></thead>
            <tbody>
                <? foreach($in_obj as $k => $v) { ?>
                    <tr><td><?=$k?></td><td><div>
                        <?=is_array($v)? implode('|',$v):$v?>
                    </div></td></tr>
                <? } ?>
            </tbody>
        </table>
    </div>
    <div class="data out">
        <h3>Output</h3>
        <table>
            <thead><tr><td>Table</td><td>Key</td><td>Value</td></tr></thead>
            <tbody>
                <? foreach($out_post as $k => $v) { ?>
                    <tr><td>wp_posts</td><td><?=$k?></td><td><div><?=$v?></div></td></tr>
                <? } ?>
                <? foreach($out_meta as $k => $v) { ?>
                    <tr><td>wp_postmeta</td><td><?=$k?></td><td><div><?=$v?></div></td></tr>
                <? } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="venue migration container">
    <div class="instructions">
        <dl>
            <dt>1</dt>
            <dd>finish this</dd>
        </dl>
    </div>
</div>