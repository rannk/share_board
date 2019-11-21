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

        $c_message = true;

        $sql = "select * from sqlite_master where tbl_name='message'";
        $ret = $this->query($sql);
        while($row = $ret->fetchArray()) {
            if($row['tbl_name'] == "message") {
                $c_message = false;
            }
        }

        if($c_message) {
            $sql =<<<EOF
          CREATE TABLE message
          (id  INTEGER PRIMARY KEY AUTOINCREMENT,
          message           TEXT    NOT NULL,
          time            INTEGER     NOT NULL);
EOF;

            $this->exec($sql);
        }

        $c_tags = true;
        $sql = "select * from sqlite_master where tbl_name='tags'";
        $ret = $this->query($sql);
        while($row = $ret->fetchArray()) {
            if($row['tbl_name'] == "tags") {
                $c_tags = false;
            }
        }
        if($c_tags) {
            $sql =<<<EOF
          CREATE TABLE tags
          (tag_id  INTEGER PRIMARY KEY AUTOINCREMENT,
          tag_name           TEXT    NOT NULL,
          time            INTEGER     NOT NULL);
EOF;

            $this->exec($sql);
        }

        $c_tags = true;
        $sql = "select * from sqlite_master where tbl_name='rel_tag_message'";
        $ret = $this->query($sql);
        while($row = $ret->fetchArray()) {
            if($row['tbl_name'] == "rel_tag_message") {
                $c_tags = false;
            }
        }

        if($c_tags) {
            $sql =<<<EOF
          CREATE TABLE rel_tag_message
          (id  INTEGER PRIMARY KEY AUTOINCREMENT,
          msg_id           INTEGER    NOT NULL,
          tag_id            INTEGER     NOT NULL);
EOF;

            $this->exec($sql);
        }
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

    public function deleteMsg($id) {
        $id = ceil($id);

        $sql = "delete from rel_tag_message where msg_id=$id";
        $this->exec($sql);

        $sql = "delete from message where id=$id";
        return $this->exec($sql);
    }

    public function saveTag($tag_name) {
        $tag_name = addslashes($tag_name);

        if(!$tag_name) {
            return;
        }

        $sql = "select * from tags where tag_name='$tag_name'";
        $ret = $this->query($sql);
        $row = $ret->fetchArray();
        if(!$row['tag_name']) {
            $sql = "insert into tags values(null, '$tag_name', ".time().")";
            $this->exec($sql);
            return true;
        }
    }

    public function getTags() {
        $sql = "select * from tags";
        $ret = $this->query($sql);
        $rl = array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            if($row['tag_name'])
                $rl[] = $row;
        }

        return $rl;
    }

    public function setTagRel($msg_id, $tag_arr) {
        $sql_content = "";
        $msg_id = ceil($msg_id);

        if($msg_id == 0)
            return;

        // cleanup the record
        $sql = "delete from rel_tag_message where msg_id=$msg_id";
        $this->exec($sql);

        if(is_array($tag_arr)) {
            for($i=0;$i<count($tag_arr);$i++) {
                $tag_id = ceil($tag_arr[$i]);
                if($tag_id > 0) {
                    $sql_content .= "(null,$msg_id, $tag_id),";
                }
            }

            // insert new record
            if($sql_content) {
                $sql_content = substr($sql_content, 0, -1);
                $sql = "insert into rel_tag_message values " . $sql_content;
                return $this->exec($sql);
            }
        }

        return true;
    }

    public function getMsgByTag($tag_id) {
        $sql = "select * from rel_tag_message r1 inner join message m on r1.msg_id=m.id where r1.tag_id=$tag_id";
        $ret = $this->query($sql);
        $rl = array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                $rl[] = $row;
        }

        return $rl;
    }

    public function getTagRel($msg_id) {
        $msg_id = ceil($msg_id);
        $sql = "select * from rel_tag_message where msg_id=$msg_id";
        $ret = $this->query($sql);
        $rl = array();
        while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            if($row['msg_id'])
                $rl[] = $row;
        }

        return $rl;
    }
}