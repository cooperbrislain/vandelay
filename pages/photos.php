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
$photos = [];
while ($row = mysqli_fetch_assoc($res)) {
    $photo = new stdClass();
    $photo->v_id = $row['v_id'];
    $photo->file = $row['photo'];
    $photo->wp_id = $row['wp_id'];
    $photo->alt = $row['alt'];
    $photo->cover = $row['cover'];
    $photos[] = $photo;
}
?>
<table>
    <thead>
        <tr>v_id<td></td>wp_id<td></td>filename</td></tr>
    </thead>
    <tbody>
        <? foreach ($photos as $photo) {
            echo <<<HTML
                <tr>
                    <td>{$photo->v_id}</td>
                    <td>{$photo->wp_id}</td>
                    <td><img src="{$source_baseurl}/{$photo->v_id}/{$photo->file}"></td>
                </tr>";
                HTML;
        } ?>
    </tbody>
</table>
