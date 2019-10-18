<?php
$filename = $_GET['fn'];

//$filename = urlencode($filename);

$file = __DIR__ . "/data/files/" . urlencode(basename($filename));
$file_arr = pathinfo($file);
$ext = $file_arr['extension'];
//echo $ext; exit;
if(file_exists($file)) {
    if($ext == "png" || $ext == "jpg" || $ext == "gif" || $ext == "jpeg" || $ext == "bmp") {
        header("Content-Type: image/jpeg;text/html; charset=utf-8");
    }else {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
    }

    readfile($file);
    exit;
}