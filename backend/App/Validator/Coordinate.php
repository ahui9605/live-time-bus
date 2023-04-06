<?php
namespace App\Validator;

/**
 * 坐标验证器
 */
class Coordinate extends Base
{
    /**
     * 规则
     */
    protected $rules = [
        'store' => [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ],

        'index' => [
            'southwest' => 'required',
            'northeast' => 'required'
        ],

        'broadcast' => [
            'southwest' => 'required',
            'northeast' => 'required'
        ]
    ];

    /**
     * 错误信息
     */
    protected $messages = [
        'store' => [
            'latitude.required' => 'latitude is empty',
            'latitude.numeric' => 'latitude is not a number',
            'longitude.required' => 'longitude is empty',
            'longitude.numeric' => 'longitude is not a number',
        ],

        'index' => [
            'southwest.required' => 'the param southwest is empty',
            'northeast.required' => 'the param northeast is empty',
        ],

        'broadcast' => [
            'southwest.required' => 'the param southwest is empty',
            'northeast.required' => 'the param northeast is empty',
        ],
    ];
}