<?php
namespace App\HttpController;

use App\Constant\Error;
use App\Queue\CoordinateQueue;
use App\Service\CoordinateService;
use App\Validator\Coordinate as ValidatorCoordinate;
use EasySwoole\Queue\Job;

/**
 * 坐标
 */
class Coordinate extends Base
{
    protected $needLogins = ['store'];

    /**
     * 列表
     */
    public function index()
    {
        // 获取参数
        $data = $this->request()->getBody()->__toString();
        $data = json_decode($data, true);
        if (empty($data)) {
            return $this->jsonReturn(Error::PARAM_ERROR, 'the param is invalid');
        }

        // 组装快速验证
        $validator = new ValidatorCoordinate('index');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules());
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        $result = CoordinateService::getCoordinatesByRegin($data);
        return $this->jsonReturn(Error::SUCCESS, 'succeed', $result);
    }

    /**
     * 新增
     */
    public function store()
    {
        // 获取参数
        $data = $this->request()->getBody()->__toString();
        $data = json_decode($data, true);
        if (empty($data)) {
            return $this->jsonReturn(Error::PARAM_ERROR, 'the param is invalid');
        }

        // 组装快速验证
        $validator = new ValidatorCoordinate('store');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules());
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        $job = new Job();
        $job->setJobData(json_encode(array_merge($data, [
            'user_id' => $this->user['id']
        ]), 256));

        $queue = CoordinateQueue::getInstance();
        $result = $queue->producer()->push($job);
        if ($result) {
           return $this->jsonReturn(Error::SUCCESS, 'succeed');
        }

        return $this->jsonReturn(Error::FAILED, 'failed');
    }
}