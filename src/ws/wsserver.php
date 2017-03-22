<?php

if (php_sapi_name() !== "cli") {
    exit();
}

use Workerman\Worker;

require_once __DIR__."/conf.php";
require_once __DIR__."/Workerman/Autoloader.php";
require_once __DIR__."/../common/account.php";

$worker = new Worker("websocket://0.0.0.0:$port", $context);
if (array_key_exists("ssl")) {
    $worker->transport = "ssl";
}
$worker->count = 1;
$worker->onWorkerStart = function ($worker) {
    global $inner_port;
    $inner_text_worker = new Worker("Text://127.0.0.1:$inner_port");
    $inner_text_worker->onMessage = function ($connection, $buffer) {
        global $worker;
        // $obj = json_decode($buffer);
        broadcast($buffer);
    };
    $inner_text_worker->listen();
};

$worker->connections = array();

$worker->onMessage = function ($connection, $data) use ($worker) {
    if (empty($connection->token)) {
        if (Account::ValidateToken($data)) {
            $connection->token = $data;
            $worker->connections[$connection->token] = $connection;
            return;
        } else {
            $connection->close();
        }
    }
};

$worker->onClose = function ($connection) use ($worker) {
    global $worker;
    if (isset($connection->token)) {
        unset($worker->connections[$connection->token]);
    }
};

function broadcast($message)
{
    global $worker;
    foreach ($worker->connections as $connection) {
        $connection->send($message);
    }
}

Worker::runAll();
