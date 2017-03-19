<?php

require_once __DIR__."/../conf.php";
require_once __DIR__."/../common/account.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_REQUEST["token"]) &&
        !empty($_POST["msg"]) &&
        strlen($_REQUEST["token"]) == 32 &&
        ctype_xdigit($_REQUEST["token"])) {
        $token = $_REQUEST["token"];
        if (Account::ValidateToken($token)) {
            $msg = str_replace("\n", "<br>", htmlspecialchars($_POST["msg"]));
            $query = Data::$pdo->prepare(Data::account_QUERY_id_BY_token);
            if ($query->execute([$token])) {
                if ($uid = $query->fetch()["id"]) {
                    $add = Data::$pdo->prepare(Data::chatlog_ADD_uid_time_content);
                    if ($add->execute([$uid, gmdate(Data::DATE_FORMAT, time()), $msg])) {
                        echo "Succeed.";
                    } else {
                        // SQL failed
                    }
                } else {
                    // Should never achieve, otherwise there is something wrong in database
                    throw new Exception("Fatal error");
                }
            } else {
                // SQL failed
            }
        } else {
            // Invalid token
        }
    } else {
        // Illegal arguments
    }
} else {
    // Illegal method
    echo "Error: Illegal method. Use POST method instead.";
}
