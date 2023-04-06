<?php
namespace App\Model;

use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * 用户模型
 */
class UserModel extends AbstractModel
{
    /**
     * 关联表
     * @var string 
     */
    protected $tableName = 'users';
    protected $autoTimeStamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 表的获取
     * @return Table
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id', 11)->setIsPrimaryKey(true)->setColumnName('主键id');
        $table->colTinyInt('role_id', 1)->setColumnName('角色id，1普通用户，2校车司机');
        $table->colChar('avatar', 255)->setColumnName('头像');
        $table->colChar('username', 64)->setColumnName('用户名');
        $table->colChar('password', 128)->setColumnName('密码');
        $table->colChar('desc', 360)->setColumnName('描述');
        $table->colTinyInt('is_reporting', 1)->setColumnName('是否开启上报，0否，1是');
        $table->colTinyInt('is_online', 1)->setColumnName('是否在线，0否，1是');
        $table->colInt('last_login_time', 11)->setColumnName('最近一次登录时间');
        $table->colInt('last_refresh_time', 11)->setColumnName('最近一次刷新时间');
        $table->colTinyInt('status', 1)->setColumnName('状态，0禁用，1正常');
        return $table;
    }
}