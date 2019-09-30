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
                $rt[$i]['message'] = addAtagForLink(urldecode($rt[$i]['message']));
                $r = $rt;
            }
        }else {
            $number = count($rt)-1;
            for($i=$number;$i>=0;$i--) {
                $rt[$i]['message'] = addAtagForLink(urldecode($rt[$i]['message']));
                $r[] = $rt[$i];
            }
        }
        $db->close();
        return $r;
    }
}