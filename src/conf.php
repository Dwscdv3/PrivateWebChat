<?php

class Constants
{
    public static $DATA_PATH, $ACCOUNT_PATH, $TOKEN_PATH, $IMAGE_PATH, $CHAT_LOG_PATH;
    
    static function init()
    {
        Constants::$DATA_PATH = $_SERVER["DOCUMENT_ROOT"]."/data";
        Constants::$ACCOUNT_PATH = self::$DATA_PATH."/accounts/";
        Constants::$TOKEN_PATH = self::$DATA_PATH."/tokens/";
        Constants::$IMAGE_PATH = self::$DATA_PATH."/images/";
        Constants::$CHAT_LOG_PATH = self::$DATA_PATH."/chatlog";
    }
}

Constants::init();
