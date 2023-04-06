<?php
namespace App\Service;

use App\Model\UserModel;
use EasySwoole\Jwt\Jwt;

/**
 * 用户
 */
class UserService
{
    /**
     * 获取AccessToken
     */
    public static function getAccessToken(UserModel $user): array
    {
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        $jwtconf = $instance->getConf('JWT');

        $jwtObject = Jwt::getInstance()->setSecretKey($jwtconf['secret_key'])->publish();
        $jwtObject->setAlg($jwtconf['alg']);
        $jwtObject->setAud($user->username);

        $expiredAt = time() + $jwtconf['expired_in'];
        $jwtObject->setExp($expiredAt);
        $jwtObject->setIat(time());
        $jwtObject->setIss($jwtconf['iss']);
        $jwtObject->setJti(md5(time()));
        $jwtObject->setSub('login');

        // 自定义数据
        $jwtObject->setData([
            'user_id' => $user->id
        ]);

        // 生成token
        return [
            'expired_in' => $jwtconf['expired_in'],
            'access_token' => $jwtObject->__toString()
        ];
    }

    /**
     * 获取用户信息
     */
    public static function getUserByAccessToken(string $accessToken)
    {
        try {
            $instance = \EasySwoole\EasySwoole\Config::getInstance();
            $jwtconf = $instance->getConf('JWT');
            $jwtObject = Jwt::getInstance()->setSecretKey($jwtconf['secret_key'])->decode($accessToken);
            if ($jwtObject->getStatus() != 1 || $jwtObject->getExp() <= time() || empty($jwtObject->getData()['user_id'])) {
                return [];
            }

            $userId = $jwtObject->getData()['user_id'];
            $user = UserModel::create()->get([
                'id' => $userId
            ]);

            if (empty($user) || $jwtObject->getAud() != $user->username) {
                return [];
            }

            if ($jwtObject->getExp() <= time()) {
                if ($user->is_online != 0) {
                    $user->where([
                        'id' => $user
                    ])->update([
                        'is_online' => 0
                    ]);
                }
                
                return [];
            }

            return $user;
        } catch (\Exception $e) {
            return [];
        } 
    }
}