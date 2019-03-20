<?php

// +----------------------------------------------------------------------
// | 计划任务 - 示例脚本
// +----------------------------------------------------------------------

namespace Cron\CronScript;

use Cron\Base\Cron;
use Think\Exception;


class Demo extends Cron
{

    //任务主体
    public function run($cronId)
    {
        \Think\Log::record("我执行了计划任务事例 Demo.class.php！");

        //模拟长时间执行
        sleep(5);
        $i = rand(1, 10);
        //0-3 正常
        if ($i <= 3) {
            var_dump('this is demo cron');
        }
        //3-6 异常
        if ($i > 3 && $i <= 6) {
            //模拟异常Exception
            throw new Exception('突然出错了');
        }
        //6以上 错误
        if ($i > 6) {
            //模拟错误
            test();
        }

        var_dump('finish..');

    }

}
