<?php
/**
 * 发送数据
 */
require_once "libs/MessageClass.php";
$time = $_POST['time'];

$msgObj = new MessageClass();
$data = $msgObj->getMessage($time);

$r['status'] = 1;
$r['data'] = $data;

echo json_encode($r);