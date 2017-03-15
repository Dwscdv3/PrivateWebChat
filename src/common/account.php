<?php

require_once $_SERVER['DOCUMENT_ROOT']."/conf.php";

class Account
{
    public static function GetToken($username, $password)
    {
        if (file_exists(Constants::$ACCOUNT_PATH.$username)) {
            $_accountInfo = explode("\n", file_get_contents(Constants::$ACCOUNT_PATH.$username));
            $correctHash = $_accountInfo[0];
            $salt = $_accountInfo[1];
            $pwdHash = hash("sha512", $password.$salt);
            if ($pwdHash === $correctHash) {
                $r = random_bytes(16);
                file_put_contents(Constants::$TOKEN_PATH.$r, $username);
                return $r;
            }
        }
        return false;
    }
    public static function ValidateToken($token)
    {
        if (file_exists(Constants::$TOKEN_PATH.$token)) {
            if (isset($_SERVER["REMOTE_ADDR"])) {
                $_tokenInfo = explode("\n", file_get_contents(Constants::$TOKEN_PATH.$token));
                $ip = $_tokenInfo[0];
                $expire = (int)$_tokenInfo[1];
                if ($_SERVER["REMOTE_ADDR"] === $ip) {
                    if (time() <= $expire) {
                        return true;
                    }
                }
            }
            unlink(Constants::$TOKEN_PATH.$token);
        }
        return false;
    }
}
