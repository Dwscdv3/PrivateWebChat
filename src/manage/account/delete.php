<?php

require_once $_SERVER['DOCUMENT_ROOT']."/conf.php";

if ($argc == 2) {
    if (file_exists(Constants::$ACCOUNT_PATH.$argv[1])) {
        unlink(Constants::$ACCOUNT_PATH.$argv[1]);
    }
}
