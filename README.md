## Project Description

---

### Project structure

---

```code
-- backend  backend code
   |—— dev.php Configuration file for development environment
   |—— produce.php Configuration file for production environment
   |—— easyswoole Script to start the service
   └── ... Other code

-- front Front-end code
   └── uniapp Uniapp code
     |── unpackage Compiled code (code to be deployed to the server)
     └── ... Other code is source code
```

### Front-end code

---

> The front-end is written using the Uniapp framework. If you need to adjust it, you can refer to the Uniapp documentation, whose syntax is similar to Vue.
> [Uniapp](https://zh.uniapp.dcloud.io/tutorial/)

Deployment method: Deploy the code in the unpackage directory of the front-end code directly to the server.

### Backend code

---

> The back-end uses the EasySwoole framework. If you need to adjust it, you can refer to the EasySwoole documentation

Deployment method: You can refer to the [EasySwoole](https://www.easyswoole.com/)

### Database

---

> The database uses Mysql 5.7

Table description:

1. User table

```sql
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key ID',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Avatar',
  `role_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Role ID',
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Username',
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Password',
  `desc` varchar(360) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Description',
  `last_login_time` int NOT NULL DEFAULT '0' COMMENT 'Last login time',
  `last_refresh_time` int NOT NULL DEFAULT '0' COMMENT 'Last refresh time',
  `is_reporting` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether to enable reporting, 0 for no, 1 for yes',
  `is_online` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether online, 0 for no, 1 for yes',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status, 0 for disabled, 1 for normal',
  `created_at` int NOT NULL DEFAULT '0' COMMENT 'Creation time',
  `updated_at` int NOT NULL DEFAULT '0' COMMENT 'Update time',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User table';

```

2. bus station table

```sql
CREATE TABLE `stations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key ID',
  `icon` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Station icon',
  `title` varchar(90) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Station title',
  `latitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT 'Latitude',
  `longitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT 'Longitude',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status, 0 for unavailable, 1 for available',
  `created_at` int NOT NULL DEFAULT '0' COMMENT 'Creation time',
  `updated_at` int NOT NULL DEFAULT '0' COMMENT 'Update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Station coordinate table';

```

> The icon field is not currently used, and a fixed image is displayed on the front end.

3. User coordinate table

```sql
CREATE TABLE `coordinates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key ID',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT 'User ID',
  `latitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT 'Latitude',
  `longitude` decimal(20,6) NOT NULL DEFAULT '0.000000' COMMENT 'Longitude',
  `created_at` int NOT NULL DEFAULT '0' COMMENT 'Creation time',
  `updated_at` int NOT NULL DEFAULT '0' COMMENT 'Update time',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Coordinate table';

```

### AWS Server Configuration

> The server uses aaPanel, and specific information can be found in the aaPanel documentation
> [aaPanel](https://www.aapanel.com/new/index.html)

Note that the server is use t2.medium instance type provides 2 vCPUs, 4 GB of memory, and moderate network performance. Make sure that this instance type meets the requirements of your application and workload depending on how many buses and users are using the server. 
