<?php

require_once __DIR__."/../conf.php";
require_once __DIR__."/pdo.php";

class Account
{
    public static function GetToken($username, $password, $expire = null)
    {
        if (isset($_SERVER["REMOTE_ADDR"])) {
            $query = Data::$pdo->prepare(Data::account_QUERY_password_salt_BY_username);
            if ($query->execute([$username])) {
                if ($_accountInfo = $query->fetch()) {
                    $correctHash = $_accountInfo["password"];
                    $salt = $_accountInfo["salt"];
                    $pwdHash = hash("sha512", $password.$salt);
                    if ($pwdHash === $correctHash) {
                        $datetime = new DateTime();
                        if ($expire === null) {
                            $datetime->setTimestamp(time());
                            $datetime->add(new DateInterval("P7D"));
                        } else {
                            $datetime->setTimestamp($expire);
                        }
                        $expireString = $datetime->format(Data::DATE_FORMAT);
                        
                        $token = bin2hex(random_bytes(16));
                        $update = Data::$pdo->prepare(Data::account_SET_token_ipv4_expire_BY_username);
                        if ($update->execute([$token, ip2long($_SERVER["REMOTE_ADDR"]), $expireString, $username])) {
                            return $token;
                        } else {
                            // SQL failed
                        }
                    }
                }
            } else {
                // SQL failed
            }
        }
        return false;
    }
    public static function ValidateToken($token)
    {
        if (isset($_SERVER["REMOTE_ADDR"])) {
            $query = Data::$pdo->prepare(Data::account_QUERY_ipv4_expire_BY_token);
            if ($query->execute([$token])) {
                if ($_tokenInfo = $query->fetch()) {
                    $ip = intval($_tokenInfo["ipv4"]);
                    $expire = strtotime($_tokenInfo["expire"]);
                    if (ip2long($_SERVER["REMOTE_ADDR"]) === $ip) {
                        if (time() <= $expire) {
                            return true;
                        }
                    }
                    $remove = Data::$pdo->prepare(Data::account_REMOVE_token_ipv4_expire_BY_token);
                    $remove->execute([$token]);
                } else {
                    // Token not found
                }
            } else {
                // SQL failed
            }
        }
        return false;
    }
}
