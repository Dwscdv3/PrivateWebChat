<?php

require_once __DIR__."/conf.php";

if (file_exists("./.setuplock")) {
    exit("Forbidden");
}

$domain = $_REQUEST["db_domain"];
$port = intval($_REQUEST["db_port"]) > 1 && intval($_REQUEST["db_port"]) < 65536 ? intval($_REQUEST["db_port"]) : 3306;
$db = $_REQUEST["db_name"];
$username = $_REQUEST["db_username"];
$password = $_REQUEST["db_password"];

if (isset($domain) &&
    isset($db) &&
    isset($username) &&
    isset($password)) {
    if (!file_exists(Constants::$DATA_PATH)) {
        mkdir(Constants::$DATA_PATH, 0644, true);
        echo "<strong>Create directory:</strong> " . Constants::$DATA_PATH."<br>";
    }

    echo "Connecting to database... ";
    $sql = new PDO("mysql:host=$domain;dbname=$db;port=$port", $username, $password);
    if (mysqli_connect_errno()) {
        echo "<strong>Failed:</strong> ".mysqli_connect_error();
        exit();
    }
    echo "<strong>OK</strong><br>";

    $create_table_account = "
    CREATE TABLE IF NOT EXISTS account (
        id INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(32) NOT NULL,
        password CHAR(128) NOT NULL,
        salt CHAR(128) NOT NULL,
        token CHAR(32),
        ipv4 INT UNSIGNED,
        expire DATETIME,
        PRIMARY KEY (id, username)
    )
    ENGINE = InnoDB
";
    $sql->exec($create_table_account);
    echo "<strong>Execute:</strong> ".str_replace(" ", "&nbsp;", str_replace("\n", "<br>", $create_table_account));

    $create_table_chatlog = "
    CREATE TABLE IF NOT EXISTS chatlog (
        id INT NOT NULL AUTO_INCREMENT,
        uid INT NOT NULL,
        time DATETIME NOT NULL,
        content BLOB NOT NULL,
        PRIMARY KEY (id)
    )
    ENGINE = InnoDB
";
    $sql->exec($create_table_chatlog);
    echo "<strong>Execute:</strong> ".str_replace(" ", "&nbsp;", str_replace("\n", "<br>", $create_table_chatlog));

    file_put_contents(Constants::$DB_INFO_PATH,
        "$domain\n".
        "$port\n".
        "$db\n".
        "$username\n".
        "$password");
    echo "<strong>Create file:</strong> ".Constants::$DB_INFO_PATH."<br>";

    if (!file_exists(Constants::$IMAGE_PATH)) {
        mkdir(Constants::$IMAGE_PATH, 0644, true);
        echo "<strong>Create directory:</strong> ".Constants::$IMAGE_PATH."<br>";
    }

    if (!file_exists(Constants::$AVATAR_PATH)) {
        mkdir(Constants::$AVATAR_PATH, 0644, true);
        echo "<strong>Create directory:</strong> ".Constants::$AVATAR_PATH."<br>";
    }

    echo "<strong style=\"color: red\"> ; Finally, make sure the \"data\" and \"manage\" directory is forbid to access from web.</strong><br>";
    
    file_put_contents("./.setuplock", "");
}
