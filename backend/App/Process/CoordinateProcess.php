<?php
namespace App\Process;

use App\Model\CoordinateModel;
use App\Queue\CoordinateQueue;
use App\Service\CoordinateService;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Queue\Job;

/**
 * 坐标
 */
class CoordinateProcess extends AbstractProcess
{
    protected function run($arg)
    {
        CoordinateQueue::getInstance()->consumer()->listen(function (Job $job){
            $data = json_decode($job->getJobData(), true);
            $model = CoordinateModel::create([
                'user_id' => $data['user_id'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude']
            ]);

            if ($model->save()) {
                /**
                 * 坐标上报成功后，向所有在线的客户端进行广播
                 * 通知客户端进行更新
                 */
                CoordinateService::broadcast();  
            }
        });
    }
}
