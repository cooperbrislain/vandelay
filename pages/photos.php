<?php
require_once('includes/opts.php');
require_once('includes/data.php');

$source_baseurl = 'http://smallweddings.com/ven_img';

$q = <<<QUERY
    SELECT *, venue_photos.v_id AS v_id FROM venue_photos
    INNER JOIN v_import ON venue_photos.v_id = v_import.v_id
    WHERE status = 1
    ORDER BY v_import.wp_id DESC
QUERY;
$res = doq($q);
$venues = [];
while ($row = mysqli_fetch_assoc($res)) {
    if (!$venues[$row['v_id']]) {
        $venue = new stdClass();
        $venue->v_id = $row['v_id'];
        $venue->wp_id = $row['wp_id'];
        $venue->photos = [];
        $venues[$row['v_id']] = $venue;
    }
    $photo = new stdClass();
    $photo->file = $row['photo'];
    $photo->alt = $row['alt'];
    $photo->cover = $row['cover'];
    $venues[$row['v_id']]->photos[] = $photo;
}
?>
<table>
    <thead><tr><td>v_id</td><td>wp_id</td><td>filename</td></tr></thead>
    <tbody>
    <? foreach ($venues as $venue) { ?>
        <tr>
            <td><?=$venue->v_id?></td>
            <td><?=$venue->wp_id?></td>
            <td>
                <div class="import photos">
                    <? foreach ($venue->photos as $photo) {
                        echo <<<HTML
                            <div class="item">
                                <img src="{$source_baseurl}/{$venue->v_id}/{$photo->file}" alt="photo"> 
                            </div>
                        HTML;
                    } ?>
                </div>
            </td>
        </tr>
    <? } ?>
    </tbody>
</table>
