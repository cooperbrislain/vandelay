<?php
    if(empty($venues)) exit();
    if(empty($source_baseurl)) exit();
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