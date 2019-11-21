<?php
function addAtagForLink($content) {
    $pattern = '/(http[s]{0,1}:\/\/[\w\d\.\-_\/#\?\%:=@&]{1,})/i';
    $replacement = '<a href="${1}" target=_blank>${1}</a>';
    return preg_replace($pattern, $replacement, $content);
}

function turnFileToLink($content) {
    $pattern = '/\[file (\/data\/files\/)([\w\d\.\-_\/:\%\+]{1,})\]/i';
    $replacement = '<a href="download.php?fn=${1}${2}" target=_blank>${2}</a>';
    return preg_replace($pattern, $replacement, $content);
}