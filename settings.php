<?php
require_once "libs/MessageClass.php";
switch ($_REQUEST['action']) {
    case "delete":
        deleteMsg($_POST['data_id']);
        break;
    case "add_tag":
        add_tag();
        break;
    case "get_tags":
        get_tags();
        break;
    case "get_msg_tags":
        get_msg_tags();
        break;
    case "save_msg_tags":
        save_msg_tags();
        break;
    case "get_msg_by_tag":
        get_msg_by_tag();
        break;
}

function deleteMsg($id) {
    $msgObj = new MessageClass();
    if($msgObj->deleteMsg($id)) {
        $re['status'] = 1;
    }else {
        $re['status'] = 0;
        $re['msg'] = "delete error";
    }

    echo json_encode($re);
}

function get_tags() {
    $msgObj = new MessageClass();
    $tags = $msgObj->getTags();
    $re['status'] = 1;
    $re['tags'] = $tags;
    echo json_encode($re);
}

function add_tag() {
    $msgObj = new MessageClass();
    $tag_name = $_POST['tag_name'];

    if(!$tag_name) {
        $re['status'] = 0;
        $re['msg'] = "tag name is empty";
    }


    if($msgObj->saveTag($tag_name)) {
        $re['status'] = 1;
    }else {
        $re['status'] = 0;
        $re['msg'] = "add tag error";
    }

    echo json_encode($re);
}

function get_msg_tags() {
    $msg_id = $_POST['msg_id'];
    $msgObj = new MessageClass();
    $r = $msgObj->getMsgTags($msg_id);
    if($r === false) {
        $re['status'] = 0;
        $re['msg'] = "msg id is not exist";
    }else {
        $re['status'] = 1;
        $re['value'] = $r;
    }

    echo json_encode($re);
}

function save_msg_tags() {
    $msg_id = $_REQUEST['msg_id'];
    $tag_value = $_REQUEST['tag_name'];
    $msgObj = new MessageClass();
    if($msgObj->setMsgRel($msg_id, $tag_value)) {
        $re['status'] = 1;
    }else {
        $re['status'] = 0;
    }

    echo json_encode($re);
}

function get_msg_by_tag() {
    $tag_id = $_REQUEST['tag_id'];
    $msgObj = new MessageClass();
    $msg_arr = $msgObj->getMsgByTag($tag_id);
    if($msg_arr) {
        $re['status'] = 1;
        $re['lists'] = $msg_arr;
    }else {
        $re['status'] = 0;
    }

    echo json_encode($re);
}