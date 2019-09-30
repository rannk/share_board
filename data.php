<?php
/**
 * 发送数据
 */
require_once "libs/MessageClass.php";
$time = $_REQUEST['time'];
$before = $_REQUEST['before'];

$msgObj = new MessageClass();
$data = $msgObj->getMessage($time, $before);

$r['status'] = 1;
$r['data'] = $data;

echo json_encode($r);