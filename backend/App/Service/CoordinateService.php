<?php
namespace App\Service;

use App\Model\CoordinateModel;
use App\Model\StationModel;
use App\Model\UserModel;
use Carbon\Carbon;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

/**
 * 坐标
 */
class CoordinateService
{
    /**
     * 通知给一个人
     */
    public static function getCoordinatesByRegin(array $data)
    {
        // 可视区域
        $southwest = $data['southwest'];
        $northeast = $data['northeast'];
        
        // 获取可视区域里的校车坐标
        $busCoordinates = self::getBusCoordinatesByRegin($southwest, $northeast);

        // 获取可视区域里的车站坐标
        $stationCoordinates = self::getStationCoordinatesByRegin($southwest, $northeast);

        return [
            'bus' => $busCoordinates,
            'station' => $stationCoordinates
        ];
    }

    /**
     * 获取校车坐标
     */
    private static function getBusCoordinatesByRegin($southwest, $northeast): array
    {
        $userIds = UserModel::create()->where([
            'role_id' => 2,
            'status' => 1
        ])->column('id');

        if (empty($userIds)) {
            return [];
        }

        $sql = "select id, user_id, latitude, longitude, created_at from %s where latitude >= %f and latitude <= %f and longitude >= %f and longitude <= %f and id in (select max(id) as id from %s where user_id in (%s) group by user_id)";
        $coordinateTable = (new CoordinateModel())->getTableName();
        $sql = sprintf($sql, $coordinateTable, $southwest['latitude'], $northeast['latitude'], $southwest['longitude'], $northeast['longitude'], $coordinateTable, implode(',', $userIds));
        $queryBuild = new QueryBuilder();
        $queryBuild->raw($sql);
        $list = DbManager::getInstance()->query($queryBuild, true, 'default')->toArray();
        if (empty($list)) {
            return [];
        }

        $datas = $list['result'];
        foreach ($datas as &$data) {
            $data['created_at'] = Carbon::createFromTimestamp($data['created_at'], 'UTC')->format('Y-m-d H:i:s');
            $data['user'] = UserModel::create()->field('`avatar`,`username`, `desc`')->where('id', $data['user_id'])->get();
        }

        return $datas;
    }

    /**
     * 获取车站坐标
     */
    private static function getStationCoordinatesByRegin($southwest, $northeast)
    {
        $sql = "select id, icon, title, latitude, longitude, created_at from %s where latitude >= %f and latitude <= %f and longitude >= %f and longitude <= %f";
        $stationTable = (new StationModel())->getTableName();
        $sql = sprintf($sql, $stationTable, $southwest['latitude'], $northeast['latitude'], $southwest['longitude'], $northeast['longitude']);
        $queryBuild = new QueryBuilder();
        $queryBuild->raw($sql);
        $list = DbManager::getInstance()->query($queryBuild, true, 'default')->toArray();
        return $list['result'] ?? [];
    }

    /**
     * 向所有在线客户端发起更新信号
     */
    public static function broadcast()
    {
        $server = ServerManager::getInstance()->getSwooleServer();

        $startFd = 0;
        while(true) {
            $connList = $server->getClientList($startFd, 10);
            if (empty($connList)) {
                break;
            }
 
            $startFd = end($connList);
            foreach ($connList as $fd) {
                $info = $server->getClientInfo($fd);
                if (!empty($info) && $info['websocket_status'] == WEBSOCKET_STATUS_FRAME) {
                    $server->push($fd, json_encode([
                        'action' => 'update'
                    ], 256));
                }
            }
        }
    }
}