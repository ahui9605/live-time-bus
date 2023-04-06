<?php

use EasySwoole\Log\LoggerInterface;

return [
    'SERVER_HOST' => 'http://127.0.0.1:9501',
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, // 可选为：EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3,
            'document_root' => EASYSWOOLE_ROOT . '/Static',
            'enable_static_handler' => true,
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    "LOG" => [
        'dir' => null,
        'level' => LoggerInterface::LOG_LEVEL_DEBUG,
        'handler' => null,
        'logConsole' => true,
        'displayConsole' => true,
        'ignoreCategory' => []
    ],
    'TEMP_DIR' => null,

    /*################################自定义配置###############################*/
    'JWT' => [
        'secret_key' => 'FutBKoMu1ka9Uzx5CBGHvIvo6Q9Zok3T',
        'expired_in' => 3600,
        'iss' => 'school',
        'alg' => 'HMACSHA256'
    ],
    
    'MYSQL' => [
        'host'          => 'mysql', // 数据库地址
        'port'          => 3306, // 数据库端口
        'user'          => 'root', // 数据库用户名
        'password'      => 'root', // 数据库用户密码
        'timeout'       => 30, // 数据库连接超时时间
        'charset'       => 'utf8mb4', // 数据库字符编码
        'database'      => 'school', // 数据库名
    ],

    'REDIS' => [
        'host'          => 'redis', // 数据库地址
        'port'          => 6379, // 数据库端口
        'auth'          => '123456', // 数据库用户密码
    ],
];
