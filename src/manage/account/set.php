<?php

require_once $_SERVER['DOCUMENT_ROOT']."/conf.php";

if ($argc == 3) {
    $content = $argv[2]."\n".random_bytes(64);
    file_put_contents(Constants::$ACCOUNT_PATH.$argv[1], hash("sha512", $content));
    echo "Succeed.";
}
