<?php
require_once __DIR__ . "/../helpers/commonHelper.php";

class MessageClass
{

    var $latest_file;
    var $data_dir = __DIR__ . "/../data";

    public function __construct()
    {
        $this->latest_file = date("Ymd") . ".data";
    }

    public function saveMessage($message) {
        $message = trim($message);
        if(!$message)
            return;

        $message = str_replace('{', '\{', $message);
        $message = str_replace('}', '\}', $message);
        $message = str_replace('|', '\|', $message);
        $fp = fopen($this->data_dir . "/" . $this->latest_file,"a");
        fwrite($fp, '{{'. time() . "||" .$message.'}}');
        fclose($fp);
    }

    public function getMessage($time) {
        if($time) {
            $file = $this->data_dir . "/" . date("Ymd", $time) . ".data";
        }else {
            $file = $this->data_dir . "/" . $this->latest_file;
        }

        if(!file_exists($file)) {
            return;
        }

        $content = file_get_contents($file);
        $cont_arr = explode('}}', $content);
        $matches = array();
        for($i=0;$i<count($cont_arr);$i++) {
            if($cont_arr[$i]) {
                $str = str_replace('{{', "", $cont_arr[$i]);
                $str_arr = explode("||", $str);
                $cont = str_replace('\{', '{', $str_arr[1]);
                $cont = str_replace('\}', '}', $cont);
                $cont = str_replace('\|', '|', $cont);
                $cont = addAtagForLink($cont);
                $matches[] = array("time" => $str_arr[0], "content" => $cont);
            }
        }

        if($time) {
            for($i=0;$i<count($matches);$i++) {
                if($matches[$i]['time'] > $time) {
                    $r[] = $matches[$i];
                }
            }
            return $r;
        }else {
            return $matches;
        }
    }
}