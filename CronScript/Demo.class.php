<?php

// +----------------------------------------------------------------------
// | 计划任务 - 示例脚本
// +----------------------------------------------------------------------

namespace Cron\CronScript;

use Cron\Base\Cron;
use Think\Exception;

class Demo extends Cron {

	//任务主体
	public function run($cronId) {
		\Think\Log::record("我执行了计划任务事例 Demo.class.php！");
		echo 'this is demo cron';
		//模拟长时间执行
		sleep(2);
		//模拟出错了
        throw new Exception('突然出错了');
	}

}
