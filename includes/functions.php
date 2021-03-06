<?php

$tran = array( // make these a little more robust.
    'pipe' => function($v) { return explode('|', $v); },
    'comma' => function($v) { return explode(',', $v); },
    'json' => function($v) { return json_decode($v); },
    'slugify' => function($v) { return slugify($v); },
    'link_to_type' => function($arr) {
        global $types;
        $ret = array();
        foreach($arr as $v) $ret[] = slugify($types[$v]);
        return $ret;
    },
    'link_to_use' => function($arr) {
        global $uses;
        $ret = array();
        foreach($arr as $v) $ret[] = slugify($uses[$v]);
        return $ret;
    },
    'strip_tags' => function($str) {
        return strip_tags($str);
    }
);

function import_venue(int $v_id) {
    global $keys, $uses, $opts, $source;

    if (!$v_id) return false;

    $q = <<<QUERY
        SELECT *,
            venues.v_id AS v_id, 
            GROUP_CONCAT(DISTINCT type_to_venue.type_id) AS types,
            GROUP_CONCAT(DISTINCT venue_to_use.use_id SEPARATOR '|') AS uses
        FROM venues
            LEFT JOIN v_specs ON venues.v_id = v_specs.v_id
            LEFT JOIN v_import ON venues.v_id = v_import.v_id
            LEFT JOIN type_to_venue ON venues.v_id = type_to_venue.v_id
            LEFT JOIN venue_to_use ON venues.v_id = venue_to_use.v_id
            WHERE venues.v_id = {$v_id}
    QUERY;

    $res = doq($q);
    $row = mysqli_fetch_object($res);
    if (!$row) return false;
    else return $row;
}

function translate_venue(object $in) : object {
    global $uses, $opts, $tran;

    $stack = array_fill_keys(['venue','alcohol','music','accom','catering','uses'], ['pipe', 'link_to_use']);
    $stack = array_merge($stack, array_fill_keys(['types'], ['comma', 'link_to_type']));
    $stack = array_merge($stack, array_fill_keys(['social'], ['json']));

    $out = clone $in;
    foreach($stack as $k => $f) {
        if (!is_array($f)) $f = array($f);
        foreach($f as $f_) $out->$k = $tran[$f_]($out->$k);
        debug(print_r($out->$k, 1));
    }
    return $out;
}

function construct_post(object $obj) : array {
    $post = [];
    $post['post_author'] = 1;
    $post['post_content'] = $obj->descr;
    $post['post_title'] = $obj->name;
    $post['post_name'] = $obj->url;
    $post['post_excerpt'] = strip_tags($obj->descr);
    $post['post_category'] = [3];
    $post['status'] = 'draft';

    $meta = [];
    $meta['venue_name'] = $obj->name;
    $meta['venue_type'] = $obj->types;
    $meta['venue_address'] = $obj->address;
    $meta['venue_city'] = $obj->city;
    $meta['venue_state'] = $obj->state;
    $meta['venue_zip'] = $obj->zip;
    $meta['venue_website'] = 'https://'.$obj->website; // todo: make this more intelligent.
    $meta['venue_email'] = $obj->email;
    $meta['venue_phone'] = $obj->phone;
    $meta['venue_use'] = $obj->uses;
    $meta['venue_capacity'] = $obj->capacity;
    $meta['venue_parking'] = $obj->parking_cap;
    $meta['venue_availability'] = $obj->avail;
    $meta['venue_views'] = $obj->has_views;
    $meta['venue_year'] = $obj->year_est;
    $meta['venue_alcohol'] = $obj->alcohol;
    $meta['venue_music'] = $obj->music;
    $meta['venue_wheelchair'] = $obj->wheelchair;
    $meta['venue_wedding_pack'] = $obj->wed_pack_avail;
    $meta['venue_coordinator'] = $obj->coord_avail;
    $meta['venue_minister'] = $obj->minister_avail;
    $meta['venue_catering'] = $obj->catering;
    $meta['venue_social'] = (array) $obj->social;

    $meta['v_id'] = $obj->v_id;

    $post['meta_input'] = $meta;
    return $post;
}

function insert_post($post, $v_id=0) : bool {
    status("INSERT_POST: {$v_id}");
    try {
        $result = wp_insert_post($post);
        if (is_wp_error($result)) {
            throw new Exception("FAILED TO INSERT POST!");
        } else {
            if ($v_id) {
                $status = 1;
                $wp_id = $result;
                status($wp_id);
                $msg = "success!";
                $q = <<<QUERY
                    INSERT INTO v_import 
                        (v_id, status, wp_id, log, timestamp) 
                    VALUES ({$v_id}, {$status}, {$wp_id},'{$msg}', NOW()) 
                QUERY;
                $res = doq($q);
                status("INSERTED POST {$v_id}");
            }
        }
    } catch (Exception $e) {
        status($e);
        return false;
    }
    return true;
}

function fetch_photo($photo) {
    debug($photo);
    $source_baseurl = 'http://smallweddings.com/ven_img';
    $source_url = "{$source_baseurl}/{$photo->filename}";
    $wp_upload_dir = wp_upload_dir();
    $output_filename = "{$wp_upload_dir}/{$photo->filename}";
    status("CURLING {$source_url}...");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $source_url);
//    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
//    curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
//    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    status("CURL COMPLETE. RESULT:");
    debug($result);
    status("WRITING TO FILE: {$output_filename}...");
    $fp = fopen($output_filename, 'w');
    fwrite($fp, $result);
    fclose($fp);
    $filesize = filesize($output_filename);
    status ("FILE_WRITTEN, SIZE: {$filesize}");
    return $result;
}

function attach_photo($venue, $photo) {
    $wp_filetype = wp_check_filetype(basename($photo->filename), null);
    $wp_upload_dir = wp_upload_dir();
    $attachment = array(
        'guid' => $wp_upload_dir['url'] . '/' . basename($photo->filename),
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($photo->filename)),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $photo->filename, $venue->wp_id);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $photo->filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );
}

function render_fields($obj) {
    ?>
    <dl>
        <? foreach ((array) $obj as $k => $v) {
            $v = print_r($v, true);
            echo "<dt class='key'>{$k}</dt><dd class='val'>{$v}</dd>";
        } ?>
    </dl>
    <?
}

function get_photos($v_id = 0) {
    $baseurl = 'http://smallweddings.com/ven_img';
    if ($v_id) {
        $q = <<<QUERY
            SELECT *, venue_photos.v_id AS v_id FROM venue_photos
            INNER JOIN v_import ON venue_photos.v_id = v_import.v_id
            WHERE status = 1
            AND venue_photos.v_id = {$v_id}
        QUERY;
    } else {
        $q = <<<QUERY
            SELECT *, venue_photos.v_id AS v_id FROM venue_photos
            INNER JOIN v_import ON venue_photos.v_id = v_import.v_id
            WHERE status = 1
            ORDER BY v_import.wp_id DESC
        QUERY;
    }
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
    return $venues;
}

function msg($t, $m) {
    $msg_funcs = ['doq', 'debug', 'status', 'query','msg'];
    $back = debug_backtrace(true, 3);
    $l = 0;
    for ($i=0; $i<3; $i++) {
        if (in_array($back[$i]['function'], $msg_funcs)) continue;
        $l = $back[$i-1]['line'];
        $f = basename($back[$i-1]['file']);
        break;
    }
    echo <<<MSG
        <div class="msg {$t}=">
            <span class="file">{$f}</span>
            <span class="lnum">{$l}</span>
            <span class="m">{$m}</span>
        </div>
MSG;
}

function doq($q, $display = true) {
    global $source, $opts;
    if ($opts['show_queries'] && $display) msg("query", $q);
    $res = mysqli_query($source, $q);
//    $arr = mysqli_fetch_assoc($res);

//    if ($opts['show_queries']) {
//        $str = "Result: ";
//        foreach ($arr as $k => $v) {
//            $vstr = print_r($v, true);
//            $str .= "{$k}: {$vstr}; ";
//        }
//        msg("query", $str);
//    }
    return $res;
}

function debug($ob) {
    global $opts;
    if($opts['debug'])
        msg("debug", print_r($ob, true));
}

function status($msg) {
    msg("status", $msg);
}

function errmsg($msg) {
    msg("error", $msg);
}

function slugify($arg) {
    if (is_array($arg)) {
        $ret = array();
        foreach ($arg as $a) {
            $ret[] = slugify($a);
        }
        return $ret;
    } else {
        $str = strtolower($arg);
        $str = preg_replace('/\W+/','-',$str);
        $str = trim($str,'- ');
        return $str;
    }
}