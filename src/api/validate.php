<?php

require_once __DIR__."/../conf.php";

if (!empty($_REQUEST["token"])) {
    $token = $_REQUEST["token"];

    require_once __DIR__."/../common/account.php";

    if (Account::ValidateToken($token)) {
        echo json_encode(array(
            "result" => "true"
        ));
    } else {
        setcookie("token", "");
        echo json_encode(array(
            "result" => "false"
        ));
    }
}
