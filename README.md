## 项目说明

---

### 目录结构

---

```code
-- backend  后端代码
   |—— dev.php 开发环境的配置文件
   |—— produce.php 生产环境的配置文件
   |—— easyswoole 启动服务的脚本
   └── ... 其他代码

-- front 前端代码
   └── uniapp Uniapp代码
     |── unpackage  编译后的代码（要部署到服务器的代码）
     └── ...  其他代码都是源码
```

### 前端

---

> 前端是使用 uniapp 框架进行编写，如果需要调整，可以参考：[Uniapp 的文档](https://zh.uniapp.dcloud.io/tutorial/)，其语法与 VUE 类似。

部署方法：直接将前端代码中 unpackage 目录的代码部署到服务器即可。

### 后端

---

> 后端是使用 EasySwoole 框架，如果需要调整，可以参考：[EasySwoole 的文档](https://www.easyswoole.com/)

部署方法：可以参考[EasySwoole 的文档](https://www.easyswoole.com/)

### 数据库

---

> 数据库是使用 Mysql 5.7

数据表说明：

1. 用户表

```sql
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `role_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '角色id',
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `desc` varchar(360) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `last_login_time` int NOT NULL DEFAULT '0' COMMENT '最近一次登录时间',
  `last_refresh_time` int NOT NULL DEFAULT '0' COMMENT '最近一次刷新时间',
  `is_reporting` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启上报，0否，1是',
  `is_online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在线，0否，1是',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0禁用，1正常',
  `created_at` int NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';
```

2. 车站表

```sql
CREATE TABLE `stations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `icon` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '车站图标',
  `title` varchar(90) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '车站标题',
  `latitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `longitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态，0不可用，1可用',
  `created_at` int NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='车站坐标表';
```

> icon 字段暂时没有用，前台显示是固定图片

3. 用户坐标表

```sql
CREATE TABLE `coordinates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `latitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `longitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `created_at` int NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='坐标表';
```

### 服务器

> 服务器是使用 aaPanel，具体信息可以查看[aaPanel 文档](https://www.aapanel.com/new/index.html)

1. 连接服务器：

```code
ssh -i "login_key.pem" admin@ec2-44-204-163-82.compute-1.amazonaws.com
```

> login_key.pem 文件可以从 aws 后台获取

2. 获取 aaPanel 后台的入口地址：

```code
sudo bt default
```

3. 获取 aaPanel 操作指令：

```code
sudo bt
```

如果需要修改密码，则按照上面的提示，进行操作。
