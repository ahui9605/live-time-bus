<?php
namespace App\Validator;

/**
 * 用户验证器
 */
class My extends Base
{
    /**
     * 规则
     */
    protected $rules = [
        'login' => [
            'username' => 'required',
            'password' => 'required',
        ],

        'register' => [
            'role_id' => 'required|integer',
            'username' => 'required|alphaDash|lengthMin:6|lengthMax:64',
            'password' => 'required|lengthMin:6',
            'desc' => 'optional|lengthMax:360',
        ],

        'edit' => [
            'avatar' => 'optional|lengthMax:255',
            'desc' => 'optional|lengthMax:360',
        ]
    ];

    /**
     * 错误信息
     */
    protected $messages = [
        'login' => [
            'username' => 'please input your username',
            'password' => 'please input your password',
        ],

        'register' => [
            'role_id' => 'please select your role',
            'username.required' => 'please input your username',
            'username.alphaDash' => 'your username only can contain number、letter or underline',
            'username.lengthMin' => 'your username`s length must be biger than 6',
            'username.lengthMax' => 'your username`s length must be smaller than 64',
            'password.required' => 'please input your password',
            'password.lengthMin' => 'your password`s length must be biger than 6',
            'desc.lengthMax' => 'the description length must under 360',
        ],

        'edit' => [
            'avatar.lengthMax' => 'the image name is too long',
            'desc.lengthMax' => 'the description length must under 360',
        ]
    ];
}