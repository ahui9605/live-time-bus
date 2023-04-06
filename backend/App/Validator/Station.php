<?php
namespace App\Validator;

/**
 * 车站验证器
 */
class Station extends Base
{
    /**
     * 规则
     */
    protected $rules = [
        'store' => [
            'title' => 'required|lengthMax:90',
            'coordinate' => 'required',
        ],
    ];

    /**
     * 错误信息
     */
    protected $messages = [
        'store' => [
            'title.required' => 'the station name is empty',
            'title.lengthMax' => 'the length of the station name must be lower than 90',
            'coordinate.required' => 'the coordinate is empty',
        ],
    ];
}