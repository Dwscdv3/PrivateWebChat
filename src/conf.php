<?php

class Constants
{
    public static $SEPARATOR, $DATA_PATH, $DB_INFO_PATH, $AVATAR_PATH, $IMAGE_PATH;
    
    static function init()
    {
        self::$SEPARATOR = " | ";
        if (empty($_SERVER["DOCUMENT_ROOT"])) {
            self::$DATA_PATH = "./data";
        } else {
            self::$DATA_PATH = $_SERVER["DOCUMENT_ROOT"]."/data";
        }
        self::$DB_INFO_PATH = self::$DATA_PATH."/db-info";
        self::$AVATAR_PATH = self::$DATA_PATH."/avatars/";
        self::$IMAGE_PATH = self::$DATA_PATH."/images/";
    }
}

Constants::init();
