<?php

require_once __DIR__."/../conf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_REQUEST["username"]) &&
        !empty($_POST["password"])) {
        if (!empty($_REQUEST["expire"])) {
            if (substr($_REQUEST["expire"], 0, 1) === "~") {
                $baseTime = time();
                if (intval(substr($_REQUEST["expire"], 1) > 0)) {
                    $expire = time() + intval($_REQUEST["expire"]);
                } else {
                    // Illegal timestamp in `expire`
                    exit();
                }
            } else {
                $baseTime = 0;
                if (intval($_REQUEST["expire"]) > 0) {
                    $expire = intval($_REQUEST["expire"]);
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

        if ($token = Account::GetToken($_REQUEST["username"], $_POST["password"], $expire)) {
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
