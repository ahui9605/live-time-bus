<?php
namespace App\Validator;

/**
 * 基础
 */
class Base
{
    /**
     * 场景
     */
    protected $scene;

    /**
     * 验证规则
     */
    protected $rules = [];

    /**
     * 验证信息
     */
    protected $messages = [];

    /**
     * 初始化
     */
    public function __construct(string $scene)
    {
        $this->scene = $scene;
    }

    /**
     * 获取验证规则
     */
    public function getRules()
    {
        return  $this->rules[$this->scene] ?? [];
    }

    /**
     * 获取验证信息
     */
    public function getMessages()
    {
        return  $this->messages[$this->scene] ?? [];
    }
}