<?php

require_once __DIR__."/../conf.php";
require_once __DIR__."/../common/account.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_REQUEST["username"]) &&
        !empty($_POST["password"])) {
        if ($token = Account::GetToken($_REQUEST["username"], $_POST["password"])) {
            echo $token;
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
