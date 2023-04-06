<?php
namespace App\WebSocket\Controller;

use App\Service\CoordinateService;
use App\Validator\Coordinate as ValidatorCoordinate;

/**
 * 坐标
 */
class Coordinate extends Base
{
    /**
     * 广播
     */
    public function broadcast()
    {
        $params = $this->caller()->getArgs();

        $validator = new ValidatorCoordinate('broadcast');
        $validate = \EasySwoole\Validate\Validate::make($validator->getRules());
        $validate->addColumn('southwest')->isArray('the param southwest must be an array');
        $validate->addColumn('northeast')->isArray('the param northeast must be an array');
        $bool = $validate->validate($params);
        if (!$bool) {
            $this->response()->setMessage(json_encode([
                'action' => 'error',
                'error' => $validate->getError()->__toString()
            ], 256));

            return;
        }

        $coordinates = CoordinateService::getCoordinatesByRegin($params);
        $this->response()->setMessage(json_encode(array_merge($coordinates, [
            'action' => 'show',
        ]), 256));
    }
}