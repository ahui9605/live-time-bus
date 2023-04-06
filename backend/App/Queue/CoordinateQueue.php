<?php
namespace App\Queue;

use EasySwoole\Component\Singleton;
use EasySwoole\Queue\Queue;

/**
 * 坐标
 */
class CoordinateQueue extends Queue
{
    use Singleton;
}