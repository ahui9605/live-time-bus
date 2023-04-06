<?php
namespace App\HttpController;

use App\Constant\Error;
use App\Service\UserService;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;

/**
 * 基础
 */
abstract class Base extends Controller
{
    /**
     * 是否需要登录
     */
    protected $needLogins = [];

    /**
     * 用户
     */
    protected $user;

    /**
     * 请求拦截
     */
    protected function onRequest(?string $action): ?bool
    {
        // 跨域校验
        if (!$this->crossOrigin()) {
            return false;
        }

        // 登录校验
        if (in_array($action, $this->needLogins)) {
            $result = $this->checkLogin();
            if (!$result) {
                $this->jsonReturn(Error::NO_AUTH, 'please login');
                return false;
            }
        }
        
        return parent::onRequest($action);
    }

    /**
     * 获取用户信息
     */
    private function checkLogin(): bool
    {
        $headers  = $this->request()->getHeaders();
        if (empty($headers['authorization'])) {
            return false;
        }

        $accessToken = $headers['authorization'][0] ?? '';
        if (trim($accessToken)) {
            $user = UserService::getUserByAccessToken($accessToken);
            if (!empty($user)) {
                $this->user = $user;
                return true;
            }
        }

        return false;
    }

    /**
     * 跨域
     */
    private function crossOrigin()
    {
        $this->response()->withHeader('Access-Control-Allow-Origin', '*');
        $this->response()->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response()->withHeader('Access-Control-Allow-Credentials', 'true');
        $this->response()->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        if ($this->request()->getMethod() === 'OPTIONS') {
            $this->response()->withStatus(\EasySwoole\Http\Message\Status::CODE_OK);
            return false;
        }

        return true;
    }

    /**
     * JSON响应
     */
    public function jsonReturn($code, $message, $data = [])
    {
        if (!$this->response()->isEndResponse()) {
            $ret = [
                'code' => $code,
                'message' => $message
            ];
    
            if (!empty($data)) {
                $ret['data'] = $data;
            }

            $this->response()->write(json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(Status::CODE_OK);
            return true;
        } else {
            return false;
        }
    }
}