<?php
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * 车站
 */
class StationModel extends AbstractModel
{
    /**
     * 关联表
     */
    protected $tableName = 'stations';
    protected $autoTimeStamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 表的获取
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id', 11)->setIsPrimaryKey(true)->setColumnName('主键id');
        $table->colVarChar('icon', 255)->setColumnName('图标');
        $table->colVarChar('title', 90)->setColumnName('标题');
        $table->colDecimal('latitude', 20, 6)->setColumnName('维度');
        $table->colDecimal('longitude', 20, 6)->setColumnName('经度');
        $table->colTinyInt('status', 1)->setColumnName('状态，0不可用，1可用');
        return $table;
    }
}