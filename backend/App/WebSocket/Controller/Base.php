<?php
namespace App\WebSocket\Controller;

use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * 基础控制器
 */
class Base extends Controller
{

    /**
     * JSON响应
     */
    public function json(int $code, string $message, array $data = [])
    {
        $message = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        $this->response()->setMessage(json_encode($message, 256));
    }
}