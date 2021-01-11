<?php
require_once('includes/opts.php');
require_once('includes/data.php');

$source_baseurl = 'http://smallweddings.com/ven_img';

$venues = get_photos();

//$filename = $_FILES["file"]["attachment"];

//$post_id = $_POST["post_id"];

//$filetype = wp_check_filetype(basename($filename), null);

//$wp_upload_dir = wp_upload_dir();

//$attachment = array(
//    'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
//    'post_mime_type' => $filetype['type'],
//    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
//    'post_content' => '',
//    'post_status' => 'inherit'
//);


//$attachment_id = wp_insert_attachment($attachment, $filename, $post_id);
//require_once(ABSPATH . 'wp-admin/includes/image.php');
//$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
//wp_update_attachment_metadata($attach_id, $attach_data);

include "includes/list_venue_photos.php";
