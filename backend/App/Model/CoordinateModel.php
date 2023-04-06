<?php
namespace App\Model;

use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * 校车
 */
class CoordinateModel extends AbstractModel
{
    /**
     * 关联表
     */
    protected $tableName = 'coordinates';
    protected $autoTimeStamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 预定义
     */
    protected $casts = [
        'coordinate'   => 'array',
    ];

    /**
     * 表的获取
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id', 11)->setIsPrimaryKey(true)->setColumnName('主键id');
        $table->colInt('user_id', 11)->setColumnName('用户id');
        $table->colDecimal('latitude', 20, 6)->setColumnName('维度');
        $table->colDecimal('longitude', 20, 6)->setColumnName('经度');
        return $table;
    }

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->hasOne(User::class, function(QueryBuilder $query) {
            $query->where('id', $this->user_id);
            return $query;
        });
    }
}