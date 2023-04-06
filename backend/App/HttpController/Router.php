<?php
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    /**
     * 初始化路由
     */
    function initialize(RouteCollector $routeCollector)
    {

        // 文件上传
        $routeCollector->post('/attachment/upload', '/Attachment/upload');
        
        // 我的
        $routeCollector->post('/my/login', '/My/login');
        $routeCollector->post('/my/register', '/My/register');
        $routeCollector->post('/my/edit', '/My/edit');
        $routeCollector->get('/my/profile', '/My/profile');
        $routeCollector->get('/my/refresh', '/My/refresh');
        $routeCollector->get('/my/logout', '/My/logout');

        // 坐标
        $routeCollector->post('/coordinate/list', '/Coordinate/index');
        $routeCollector->post('/coordinate/store', '/Coordinate/store');

        // 站点
        $routeCollector->post('/station/store', '/Station/store');
    }
}