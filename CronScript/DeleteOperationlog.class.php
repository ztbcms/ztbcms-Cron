<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\CronScript;

use Cron\Base\Cron;

/**
 * 删除计划任务日志(默认删除30日前的日志)
 *
 * 建议每日执行一次
 */
class Deleteoperationlog extends Cron {

    /**
     * 执行任务回调
     *
     * @param string $cronId
     */
    public function run($cronId) {
        \Think\Log::record("Run:DeleteCronLog");
        $time = time()- 60*60*24*60; //30日前
        $where['time'] = array('ELT',$time);
        $res = D('Cron/Operationlog')->where($where)->delete();
        echo '删除计划任务日志记录数:' . $res;
    }
}