<?php

require_once __DIR__."/../conf.php";

class Data
{
    public static $pdo;
    public const DATE_FORMAT = "Y-m-d H:i:s",
                 account_ADD_username_password_salt = "INSERT INTO account (username, password, salt) VALUES (?, ?, ?)",
                 account_QUERY_password_salt_BY_username = "SELECT password, salt FROM account WHERE username = ?",
                 account_SET_token_ipv4_expire_BY_username = "UPDATE account SET token = ?, ipv4 = ?, expire = ? WHERE username = ?",
                 account_QUERY_id_BY_token = "SELECT id FROM account WHERE token = ?",
                 account_QUERY_ipv4_expire_BY_token = "SELECT ipv4, expire FROM account WHERE token = ?",
                 account_REMOVE_token_ipv4_expire_BY_token = "UPDATE account SET token = NULL, ipv4 = NULL, expire = NULL WHERE token = ?",
                 c_QUERY_MAX_id = "SELECT MAX(id) FROM ?",
                 c_ADD_uid_time_content = "INSERT INTO ? (uid, time, content) VALUES (?, ?, ?)",
                 c_QUERY_id_uid_time_content_BY_id_RANGE = "SELECT id, uid, time, content FROM ? WHERE id >= ? AND id <= ?";

    static function init()
    {
        if (file_exists(Constants::$DB_INFO_PATH)) {
            $dbinfo = explode("\n", file_get_contents(Constants::$DB_INFO_PATH));
            $domain = $dbinfo[0];
            $port = $dbinfo[1];
            $db = $dbinfo[2];
            $username = $dbinfo[3];
            $password = $dbinfo[4];

            self::$pdo = new PDO("mysql:host=$domain;dbname=$db;port=$port", $username, $password);
        } else {
            exit("Database information hasn't been configured yet.");
        }
    }
}

Data::init();
