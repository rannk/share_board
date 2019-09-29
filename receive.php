<?php
/**
 * 接收数据并处理
 */
require_once "libs/MessageClass.php";

$content = trim($_POST['input_content']);
$msgObj = new MessageClass();
$msgObj->saveMessage($content);

