<?php

$base_folder = null;
$web_folder = null;
$media_folder = null;
$media_images_folder = null;
$public_images_folder = null;

$base_folder = dirname( __FILE__ );
$web_folder = $base_folder . DIRECTORY_SEPARATOR . 'web';
while( !is_dir( $web_folder ) ) {
    $base_folder = dirname( $base_folder );
    $web_folder = $base_folder . DIRECTORY_SEPARATOR . 'web';
}

$media_folder = $base_folder . DIRECTORY_SEPARATOR . 'media_files';
$media_images_folder = $media_folder . DIRECTORY_SEPARATOR . 'images';
$public_images_folder = $web_folder . DIRECTORY_SEPARATOR . 'images';
$upload_folder = $web_folder . DIRECTORY_SEPARATOR . 'upload';

$fileTypes = array('jpg','jpeg','gif','png');
$verifyToken = md5('jladserv_images' . $_POST['timestamp']);
if(!empty($_FILES) && $_POST['token'] == $verifyToken) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetFile = $upload_folder . $_FILES['Filedata']['name'];
}

