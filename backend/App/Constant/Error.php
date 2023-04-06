<?php
namespace App\Constant;

/**
 * 错误码
 */
class Error
{
    const SUCCESS = 0; // 操作成功
    const FAILED = 1; // 操作失败
    const PARAM_ERROR = 4000; // 参数错误
    const NO_RESULT = 4001; // 没有记录
    const ACCOUNT_ERROR = 4002; // 账号错误
    const IS_EXISTS = 4003; // 相同账号已存在
    const NO_AUTH = 4004; // 请登录
}