<?php
/** TODO:
 *    Validate whether the sender has permission to a specific channel
 */
require_once __DIR__."/../conf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" || $_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    if (!empty($_JSON["token"]) &&
        !empty($_JSON["msg"]) &&
        strlen($_JSON["token"]) == 32 &&
        ctype_xdigit($_JSON["token"])) {
        require_once __DIR__."/../common/pdo.php";
        require_once __DIR__."/../common/account.php";

        $token = $_JSON["token"];
        $cid = isset($_JSON["cid"]) ? intval($_JSON["cid"]) : 0;
        if (Account::ValidateToken($token)) {
            $msg = trim($_JSON["msg"]);
            $query = Data::$pdo->prepare(Data::account_QUERY_id_BY_token);
            if ($query->execute([$token])) {
                if ($uid = $query->fetch()["id"]) {
                    $add = Data::$pdo->prepare(Data::c_ADD_uid_time_content);
                    $now = time();
                    if ($add->execute(["c_$cid", $uid, gmdate(Data::DATE_FORMAT, $now()), $msg])) {
                        $client = stream_socket_client("tcp://127.0.0.1:36502", $ws_errno, $ws_errmsg, 1);
                        $data = array(
                            "cid" => $cid,
                            "id" => $pdo->lastInsertId(),
                            "uid" => $uid,
                            "time" => $now
                        );
                        fwrite($client, json_encode($data)."\n");
                        fclose($client);
                        echo json_encode(array(
                            "status" => "1"
                        ));
                    } else {
                        // SQL failed
                        echo json_encode(array(
                            "status" => "-501",
                            "error" => "The database throwed an error."
                        ));
                    }
                } else {
                    // Should never achieve, otherwise there is something wrong in database
                    echo json_encode(array(
                        "status" => "-666",
                        "error" => "Fatal error."
                    ));
                    throw new Exception("Fatal error");
                }
            } else {
                // SQL failed
                echo json_encode(array(
                    "status" => "-501",
                    "error" => "The database throwed an error."
                ));
            }
        } else {
            // Invalid token
            echo json_encode(array(
                "status" => "-3",
                "error" => "Invalid token. Please refresh and log in again."
            ));
        }
    } else {
        // Illegal arguments
        echo json_encode(array(
            "status" => "-2",
            "error" => "Illegal arguments."
        ));
    }
} else {
    // Illegal method
    echo json_encode(array(
        "status" => "-1",
        "error" => "Illegal method. Use POST instead."
    ));
}
