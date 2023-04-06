<?php
namespace App\HttpController;

use App\Constant\Error;
use App\Model\StationModel;
use App\Validator\Station as ValidatorStation;

/**
 * 车站
 */
class Station extends Base
{
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
        $validator = new ValidatorStation('store');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules());
        $bool = $validate->validate($data);
        if (!$bool) {
            return $this->jsonReturn(Error::PARAM_ERROR, $validate->getError()->__toString());
        }

        list($latitude, $longitude) = explode(',', $data['coordinate'], 2);
        $latitude = trim($latitude);
        $longitude = trim($longitude);
        if (empty($latitude) || empty($longitude)) {
            return $this->jsonReturn(Error::PARAM_ERROR, 'the coordinate is invalid');
        }

        $exist = StationModel::create()->get([
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);

        if (!empty($exist)) {
            return $this->jsonReturn(Error::IS_EXISTS, 'there has been a station on the same coordinate');
        }

        $result = StationModel::create([
            'title' => $data['title'],
            'latitude' => trim($latitude),
            'longitude' => trim($longitude)
        ])->save();

        if ($result) {
           return $this->jsonReturn(Error::SUCCESS, 'succeed');
        }

        return $this->jsonReturn(Error::FAILED, 'failed');
    }
}
