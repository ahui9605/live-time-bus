<?php
namespace App\HttpController;

use App\Constant\Error;
use App\Model\UserModel;
use App\Service\UserService;
use App\Validator\My as ValidatorMy;

/**
 * 用户
 */
class My extends Base
{
    protected $needLogins = ['edit', 'profile', 'refresh', 'logout'];

    /**
     * 登录
     */
    public function login()
    {
        // 获取参数
        $data = $this->request()->getBody()->__toString();
        $data = json_decode($data, true);
        if (empty($data)) {
            return $this->jsonReturn(Error::PARAM_ERROR, 'the param is invalid');
        }

        // 组装快速验证
        $validator = new ValidatorMy('login');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules(), $validator->getMessages());

        // 验证结果
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        $user = UserModel::create()->get([
            'username' => $data['username'],
            'status' => 1
        ]);

        if (empty($user)) {
            return $this->jsonReturn(Error::NO_RESULT, 'account not exists');
        }

        if (!password_verify($data['password'], $user['password'])) {
            return $this->jsonReturn(Error::ACCOUNT_ERROR, 'account or password is invalid');
        }

        if ($user->is_online) {
            $instance = \EasySwoole\EasySwoole\Config::getInstance();
            $jwtconf = $instance->getConf('JWT');
            if (!empty($user->last_register_time) && $user->last_register_time + $jwtconf['expired_in'] > time() || empty($user->last_register_time) && $user->last_login_time + $jwtconf['expired_in'] > time()) {
                return $this->jsonReturn(Error::FAILED, 'the account is already logged in on other device');   
            }
        }

        // 下发登录token
        $accessToken = UserService::getAccessToken($user);
        if (!empty($accessToken)) {
            $result = UserModel::create()->where('id', $user['id'])->update([
                'is_online' => 1,
                'last_login_time' => time()
            ]);

            if ($result === false) {
                return $this->jsonReturn(Error::FAILED, $user->lastQueryResult()->getLastError()); 
            }
        }

        return $this->jsonReturn(Error::SUCCESS, 'succeed', [
            'user' => $user->hidden(['password']),
            'access_token' => $accessToken
        ]);
    }

    /**
     * 注册
     */
    public function register()
    {
        // 获取参数
        $data = $this->request()->getBody()->__toString();
        $data = json_decode($data, true);
        if (empty($data)) {
            return $this->jsonReturn(Error::PARAM_ERROR, 'the param is invalid');
        }

        // 验证结果
        $validator = new ValidatorMy('register');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules(), $validator->getMessages());
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        $user = UserModel::create()->get([
            'username' => $data['username'],
        ]);

        if (!empty($user)) {
            return $this->jsonReturn(Error::NO_RESULT, 'account exists');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $model = UserModel::create(array_filter($data, function($key) {
            if (in_array($key, ['role_id', 'avatar', 'username', 'password', 'desc'])) {
                return true;
            }
        }, ARRAY_FILTER_USE_KEY ));

        if ($model->save()) {
            return $this->jsonReturn(Error::SUCCESS, 'succeed');
        }

        return $this->jsonReturn(Error::FAILED, 'failed'); 
    }

    /**
     * 编辑
     */
    public function edit()
    {
        // 获取参数
        $data = $this->request()->getBody()->__toString();
        $data = json_decode($data, true);
        if (empty($data)) {
            return $this->jsonReturn(Error::PARAM_ERROR, 'the param is invalid');
        }

        // 验证结果
        $validator = new ValidatorMy('edit');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules(), $validator->getMessages());
        $validate->addColumn('is_reporting')->optional()->inArray([0,1], true, 'the param is_reporting only can be 0 or 1');
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        if (isset($data['avatar'])) {
            $data['avatar'] = $data['avatar'][0]['url'] ?? '';
        }
        
        $result = $this->user->where('id', $this->user['id'])->update(array_filter($data, function($key) {
            if (in_array($key, ['avatar', 'desc', 'is_reporting'])) {
                return true;
            }
        }, ARRAY_FILTER_USE_KEY ));

        if ($result) {
            return $this->jsonReturn(Error::SUCCESS, 'succeed');
        }

        return $this->jsonReturn(Error::FAILED, $this->user->lastQueryResult()->getLastError()); 
    }

    /**
     * 刷新
     */
    public function refresh()
    {
        $accessToken = UserService::getAccessToken($this->user);
        if (!empty($accessToken)) {
            $result = UserModel::create()->where('id', $this->user['id'])->update([
                'last_refresh_time' => time()
            ]);

            if (!$result) {
                return $this->jsonReturn(Error::FAILED, 'failed');
            }
        }

        return $this->jsonReturn(Error::SUCCESS, 'succeed', [
            'user' => $this->user->hidden(['password']),
            'access_token' => $accessToken
        ]);
    }

    /**
     * 详情
     */
    public function profile()
    {
        $user = $this->user->hidden(['password']);
        if ($user->role_id == 1) {
            $user->role_name = 'general user';
        } else if ($user->role_id == 2) {
            $user->role_name = 'school bus driver';
        }

        return $this->jsonReturn(Error::SUCCESS, 'succeed', $user); 
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $result = $this->user->where([
            'id' => $this->user['id']
        ])->update([
            'is_online' => 0
        ]);

        if ($result) {
            return $this->jsonReturn(Error::SUCCESS, 'succeed'); 
        }

        return $this->jsonReturn(Error::FAILED, 'failed'); 
    }
}