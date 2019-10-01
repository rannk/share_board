<?php

class FileClass
{

    var $file_dir;
    public function __construct()
    {
        if(!file_exists(__DIR__ . "/../data/files")) {
            mkdir(__DIR__ . "/../data/files");
        }

        $this->file_dir = __DIR__ . "/../data/files";
    }

    public function saveFiles($files) {
        $r = array();
        if(count($files) > 0) {
            foreach ($files as $file) {
                $name = $this->getNoRepeatName($file['name']);
                if(move_uploaded_file($file['tmp_name'], $this->file_dir . "/" . $name)) {
                    $r[] = $name;
                }
            }
        }
        return $r;
    }

    public function getNoRepeatName($filename)
    {
        $filename = urlencode($filename);

        $file_arr = pathinfo($filename);

        if(file_exists($this->file_dir . "/" . $filename)) {
            return $this->getNoRepeatName($file_arr['filename'] . "_copy" . "." . $file_arr['extension']);
        }

        return $filename;
    }

    public function getFileDir() {
        return $this->file_dir;
    }
}