<?php

require_once __DIR__."/../conf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" || $_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    if (!empty($_JSON["username"]) &&
        !empty($_JSON["password"])) {
        if (!empty($_JSON["expire"])) {
            if (substr($_JSON["expire"], 0, 1) === "~") {
                $baseTime = time();
                if (intval(substr($_REQUEST["expire"], 1) > 0)) {
                    $expire = time() + intval($_REQUEST["expire"]);
                } else {
                    // Illegal timestamp in `expire`
                    exit();
                }
            } else {
                $baseTime = 0;
                if (intval($_JSON["expire"]) > 0) {
                    $expire = intval($_JSON["expire"]);
                } else {
                    // Illegal timestamp in `expire`
                    exit();
                }
            }
        } else {
            $expire = time() + 7 * 86400;
        }

        require_once __DIR__."/../common/pdo.php";
        require_once __DIR__."/../common/account.php";

        if ($token = Account::GetToken($_JSON["username"], $_JSON["password"], $expire)) {
            setcookie("token", $token, $expire);
            echo json_encode(array(
                "status" => "1",
                "token" => $token
            ));
        } else {
            // Authentication failed
        }
    } else {
        //Illegal arguments
    }
} else {
    // Illegal method
    echo "Illegal method. Use POST method instead.";
}
