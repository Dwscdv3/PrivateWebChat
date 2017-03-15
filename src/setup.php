<?php

require_once $_SERVER['DOCUMENT_ROOT']."/conf.php";

if (!file_exists(Constants::$DATA_PATH)) {
    mkdir(Constants::$DATA_PATH, 0644, true);
    echo "mkdir " . Constants::$DATA_PATH."<br>";
}

if (!file_exists(Constants::$ACCOUNT_PATH)) {
    mkdir(Constants::$ACCOUNT_PATH, 0644, true);
    echo "mkdir " . Constants::$ACCOUNT_PATH."<br>";
}

if (!file_exists(Constants::$IMAGE_PATH)) {
    mkdir(Constants::$IMAGE_PATH, 0644, true);
    echo "mkdir " . Constants::$IMAGE_PATH."<br>";
}

echo "<strong> ; Finally, make sure the \"data\" and \"manage\" directory is forbid to access from web.</strong><br>";
