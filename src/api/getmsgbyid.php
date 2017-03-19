<?php

require_once __DIR__."/../conf.php";
require_once __DIR__."/../common/pdo.php";
require_once __DIR__."/../common/account.php";

if (!empty($_REQUEST["token"]) &&
    strlen($_REQUEST["token"]) == 32 &&
    ctype_xdigit($_REQUEST["token"]) && (
        !empty($_REQUEST["countbackward"]) ||
        !empty($_REQUEST["countforward"])
    ) && !(
        !empty($_REQUEST["countbackward"]) &&
        !empty($_REQUEST["countforward"])
    )) {
    $token = $_REQUEST["token"];
    
    $count = empty($_REQUEST["countbackward"])
           ? intval($_REQUEST["countforward"])
           : -intval($_REQUEST["countbackward"]);
    if ($count == 0 || abs($count) > 100) {
        // Illegal `countbackward` or `countforward`
        exit();
    }

    if (empty($_REQUEST["id"])) {
        if ($count < 0) {
            $query = Data::$pdo->prepare(Data::chatlog_QUERY_MAX_id);
            $query->execute();
            if ($row = $query->fetch()) {
                $id = $row[0];
            } else {
                $id = 0;
            }
        } else {
            $id = 1;
        }
    } else {
        $id = intval($_REQUEST["id"]);
        if (count < 0 && id <= 0) {
            exit();
        }
    }

    if (Account::ValidateToken($token)) {
        $min = $count > 0 ? $id : $id + $count + 1;
        $max = $count > 0 ? $id + $count - 1 : $id;
        $query = Data::$pdo->prepare(Data::chatlog_QUERY_id_uid_time_content_BY_id_RANGE);
        if ($query->execute([$min, $max])) {
            $jsonObject = array();
            while ($row = $query->fetch()) {
                array_push($jsonObject, array(
                    "id" => $row["id"],
                    "uid" => $row["uid"],
                    "time" => $row["time"],
                    "content" => $row["content"],
                ));
            }
            echo json_encode($jsonObject);
        }
    } else {
        // Invalid `token`
    }
} else {
    // Illegal arguments
}
