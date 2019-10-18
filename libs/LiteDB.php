<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/30
 * Time: 14:07
 */
class LiteDB extends SQLite3
{
    function __construct()
    {
        $this->open(__DIR__ . '/../data/data.db');
        $this->createTables();
    }

    public function createTables()
    {

        $sql = "select * from sqlite_master where tbl_name='message'";
        $ret = $this->query($sql);
        while($row = $ret->fetchArray()) {
            if($row['tbl_name'] == "message") {
                return;
            }
        }

        $sql =<<<EOF
          CREATE TABLE message
          (id  INTEGER PRIMARY KEY AUTOINCREMENT,
          message           TEXT    NOT NULL,
          time            INTEGER     NOT NULL);
EOF;

        $this->exec($sql);
    }

    public function saveMessage($message, $type="text", $id="") {
        $id = ceil($id);
        $message = addslashes($message);

        if(!$message) {
            return;
        }

        if($id > 0) {
            $sql = "update message set message='$message' where id=$id";
        }else {
            $sql = "insert into message values(null, '$message', ".time().")";
        }

        $this->exec($sql);
    }

    public function getMessage ($time, $before = false, $number) {
        $symbol = ">";
        if($before) {
            $symbol = "<";
        }

        $cond = "1";
        if($time) {
            $cond = "time{$symbol}{$time}";
        }
        $sql = "select * from message where $cond order by `time` desc limit 0, $number";

        $ret = $this->query($sql);
        $rl = array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            if($row['message'])
                $rl[] = $row;
        }

        return $rl;
    }
}