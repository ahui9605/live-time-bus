<?php
namespace App\WebSocket\Parser;

use EasySwoole\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Socket\Client\WebSocket;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;

/**
 * 此类是自定义的 websocket 消息解析器
 * 此处使用的设计是使用 json string 作为消息格式
 * 当客户端消息到达服务端时，会调用 decode 方法进行消息解析
 * 会将 websocket 消息 转成具体的 Class -> Action 调用 并且将参数注入
 */
class WebSocketParser implements ParserInterface
{
    /**
     * decode
     * @param  string         $raw    客户端原始消息
     * @param  WebSocket      $client WebSocket Client 对象
     * @return Caller         Socket  调用对象
     */
    public function decode($raw, $client): ?Caller
    {
        // 调用者对象
        $caller = new Caller();

        // 解析客户端原始消息
        $data = json_decode($raw, true);
        $controller = 'Exception';
        $action = 'notFound';
        if (!empty($data['controller']) && !empty($data['action'])) {
            $class = '\\App\\WebSocket\\Controller\\'. ucfirst($data['controller']);
            if (class_exists($class) && method_exists($class, $data['action'])) {
                $controller = ucfirst($data['controller']);
                $action = $data['action'];
            }
        }

        $caller->setControllerClass('\\App\\WebSocket\\Controller\\'. $controller);
        $caller->setAction($action);

        // 检查是否存在args
        $args = [];
        if (!empty($data['params'])) {
            $args = $data['params'];
        }

        if (!empty($args)) {
            $caller->setArgs($args);
        }
        
        return $caller;
    }
 
    /**
     * 编码
     */
    public function encode(Response $response, $client) : ? string
    {
        return $response->getMessage();
    }
}