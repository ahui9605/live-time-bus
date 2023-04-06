<?php
namespace EasySwoole\EasySwoole;

use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use App\WebSocket\Parser\WebSocketParser;
use App\Event\WebSocketEvent;
use App\Process\CoordinateProcess;
use App\Queue\CoordinateQueue;
use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Socket\Dispatcher;

/**
 * 注册事件
 */
class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        // 设置时区
        date_default_timezone_set('UTC');

        // 初始化数据库
        self::initDatabase();

        // 初始化队列
        self::initQueue();
    }

    /**
     * 初始化队列
     */
    private static function initQueue()
    {
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        $dbconf = $instance->getConf('REDIS');

        $redisConfig = new \EasySwoole\Redis\Config\RedisConfig(
            [
                'host' => $dbconf['host'],
                'port' => $dbconf['port'],
                'auth' => $dbconf['auth']
            ]
        );

        // 配置队列驱动器
        $driver = new \EasySwoole\Queue\Driver\RedisQueue($redisConfig, 'easyswoole_queue');
        CoordinateQueue::getInstance($driver);

        // 注册一个消费进程
        $processConfig = new \EasySwoole\Component\Process\Config([
            'processName' => 'CoordinateProcess',
            'processGroup' => 'Queue',
            'enableCoroutine' => true,
        ]);

        \EasySwoole\Component\Process\Manager::getInstance()->addProcess(new CoordinateProcess($processConfig));
    }

    /**
     * 初始化数据库配置
     */
    private static function initDatabase()
    {
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        $dbconf = $instance->getConf('MYSQL');
        $config = new Config();
        $config->setHost($dbconf['host']);
        $config->setPort($dbconf['port']);
        $config->setCharset($dbconf['charset']);
        $config->setDatabase($dbconf['database']);
        $config->setUser($dbconf['user']);
        $config->setPassword($dbconf['password']);
        $config->setTimeout(15);
        DbManager::getInstance()->addConnection(new Connection($config));
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();

        // 设置Dispatcher为WebSocket 模式
        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);

        // 设置解析器对象
        $conf->setParser(new WebSocketParser());

        // 设置异常处理
        $conf->setOnExceptionHandler(function (\Swoole\Server $server, \Throwable $throwable, string $raw, $client, \EasySwoole\Socket\Bean\Response $response) {
            $response->setMessage($throwable->getMessage() . $throwable->getFile() . $throwable->getLine());
            $response->setStatus($response::STATUS_RESPONSE_AND_CLOSE);
        });

        // 创建Dispatcher对象并注入config对象
        $websocketEvent = new WebSocketEvent();
        $register->set($register::onHandShake, function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($websocketEvent) {
            $websocketEvent->onHandShake($request, $response);
        });

        $register->set($register::onOpen, function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $request) {
            $server->push($request->fd, "welcome!");
        });

        // 注册消息处理事件，将消息数据交给Dispatcher对象处理
        $dispatch = new Dispatcher($conf);
        $register->set(EventRegister::onMessage, function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });

        $register->set(EventRegister::onClose, function (\Swoole\WebSocket\Server $server, int $fd, int $reactorId) use ($websocketEvent) {
            $websocketEvent->onClose($server, $fd, $reactorId);
        });
    }
}