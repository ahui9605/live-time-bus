<?php
namespace App\Event;

use App\Service\UserService;

/**
 * Websocket事件处理
 */
class WebSocketEvent
{
    /**
     * 握手事件
     */
    public function onHandShake(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
    
        if (
            !$this->checkWebsocket($request, $response) || 
            !$this->customHandShake($request, $response)
        ) {
            $response->end();
            return false;
        }

        return $this->secWebsocketAccept($request, $response);
    }

    /**
     * 自定义握手事件
     */
    protected function customHandShake(\Swoole\Http\Request $request, \Swoole\Http\Response $response): bool
    {
        // 可以对头部参数进行校验
        return true;
    }

    /**
     * 校验握手信息
     */
    protected function checkWebsocket(\Swoole\Http\Request $request, \Swoole\Http\Response $response): bool
    {
        if (!isset($request->header['sec-websocket-key'])) {
            return false;
        }
         
        if (
            0 === preg_match('#^[+/0-9A-Za-z]{21}[AQgw]==$#', $request->header['sec-websocket-key']) || 
            16 !== strlen(base64_decode($request->header['sec-websocket-key']))
        ) {
            return false;
        }

        return true;
    }

    /**
     * RFC规范中的WebSocket握手验证过程
     */
    protected function secWebsocketAccept(\Swoole\Http\Request $request, \Swoole\Http\Response $response): bool
    {
        $key = base64_encode(sha1($request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
         
        $headers = array(
            'Upgrade'               => 'websocket',
            'Connection'            => 'Upgrade',
            'Sec-WebSocket-Accept'  => $key,
            'Sec-WebSocket-Version' => '13',
            'KeepAlive'             => 'off',
        );
         
        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }
         
        // 发送验证后的header
        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }
         
        // 返回101状态码以切换状态
        $response->status(101);
        return true;
    }

    /**
     * 关闭事件
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        // 获取客户端信息
        $info = $server->getClientInfo($fd, $reactorId);

        // 判断此fd是否是一个有效的websocket连接
        if (!empty($info) && $info['websocket_status'] === WEBSOCKET_STATUS_FRAME) {
            if ($reactorId < 0) {
                echo "server close \n";
            }
        }
    }
}