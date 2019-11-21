<?php
require_once __DIR__ . "/../helpers/commonHelper.php";
require_once "LiteDB.php";

class MessageClass
{

    var $latest_file;
    var $data_dir;

    public function __construct()
    {
        $this->latest_file = date("Ymd") . ".data";
        $this->data_dir  = __DIR__ . "/../data";
    }

    public function saveMessage($message) {
        $db = new LiteDB();
        $message = urlencode($message);
        $db->saveMessage($message);
        $db->close();
    }

    public function getMessage($time, $before = false, $number = 20) {
        $db = new LiteDB();
        $rt = $db->getMessage($time, $before, $number);

        $r = array();
        if($before) {
            for($i=0;$i<count($rt);$i++) {
                $rt[$i]['message'] = $this->formatMsg(urldecode($rt[$i]['message']));
                $r = $rt;
            }
        }else {
            $number = count($rt)-1;
            for($i=$number;$i>=0;$i--) {
                $rt[$i]['message'] = $this->formatMsg(urldecode($rt[$i]['message']));
                $r[] = $rt[$i];
            }
        }
        $db->close();
        return $r;
    }

    public function formatMsg($message) {
        $message = turnFileToLink($message);
        return addAtagForLink($message);
    }

    public function deleteMsg($id) {
        $db = new LiteDB();
        return $db->deleteMsg($id);
    }

    public function getMsgByTag($tag_id) {
        $tag_id = ceil($tag_id);
        if($tag_id == 0)
            return;

        $db = new LiteDB();
        $rt = $db->getMsgByTag($tag_id);
        for($i=0;$i<count($rt);$i++) {
            $rt[$i]['message'] = $this->formatMsg(urldecode($rt[$i]['message']));
        }

        return $rt;
    }

    public function getTags() {
        $db = new LiteDB();
        return $db->getTags();
    }

    public function saveTag($tag_name) {
        $db = new LiteDB();
        return $db->saveTag($tag_name);
    }

    public function setMsgRel($msg_id, $tag_value) {
        $db = new LiteDB();
        $msg_id = ceil($msg_id);
        if($msg_id == 0)
            return false;

        $tag_arr = explode(",", $tag_value);
        $tags = $this->getTags();
        for($i=0;$i<count($tag_arr);$i++) {
            $tag_name = trim($tag_arr[$i]);
            if($tag_name) {
                for($j=0;$j<count($tags);$j++) {
                    if($tag_name == $tags[$j]['tag_name']) {
                        break;
                    }
                }
                if($j == count($tags)) {
                    $this->saveTag($tag_name);
                }
            }
        }

        $tags = $this->getTags();
        $tag_id_arr = array();
        for($i=0;$i<count($tag_arr);$i++) {
            $tag_name = trim($tag_arr[$i]);
            if($tag_name) {
                for($j=0;$j<count($tags);$j++) {
                    if($tag_name == $tags[$j]['tag_name']) {
                        $tag_id_arr[] = $tags[$j]['tag_id'];
                        break;
                    }
                }
            }
        }
        return $db->setTagRel($msg_id, $tag_id_arr);
    }

    public function getMsgTags($msg_id) {
        $msg_id = ceil($msg_id);
        if($msg_id == 0)
            return false;

        $db = new LiteDB();
        $rel = $db->getTagRel($msg_id);
        $tags = $this->getTags();
        $value = "";
        for($i=0;$i<count($rel);$i++) {
            $id = $rel[$i]['tag_id'];
            for($j=0;$j<count($tags);$j++) {
                if($id == $tags[$j]['tag_id']) {
                    $value .= $tags[$j]['tag_name'] . ",";
                    break;
                }
            }
        }

        if($value) {
            $value = substr($value, 0, -1);
        }

        return $value;
    }
}