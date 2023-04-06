<?php
namespace App\WebSocket\Controller;

use App\Constant\Error;

/**
 * 错误
 */
class Exception extends Base
{
    /**
     * 控制器或方法不存在
     */
    public function notFound()
    {
        $this->response()->setMessage([
            'action' => 'notice',
            'error' => 'the param controller or action does not exist or is invalid'
        ]);
    }
}