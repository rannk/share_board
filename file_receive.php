<?php
require_once "libs/MessageClass.php";
require_once "libs/FileClass.php";

$fileObj = new FileClass();
$files = $fileObj->saveFiles($_FILES);

$content = "";
print_r($files);
for($i=0;$i<count($files);$i++) {
    $content .= "[file /data/files/" .$files[$i] . "]<br>";
}
$msgObj = new MessageClass();
$msgObj->saveMessage($content);
