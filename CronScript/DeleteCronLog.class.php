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
class DeleteCronLog extends Cron {

    /**
     * 执行任务回调
     *
     * @param string $cronId
     */
    public function run($cronId) {
        \Think\Log::record("Run:DeleteCronLog");
        $limit_time = time() - 30 * 24 * 60 * 60; //30日前
        $res = D('Cron/CronLog')->where(['start_time' => ['ELT', $limit_time]])->delete();
        echo '删除计划任务日志记录数:' . $res;
        $res = D('Cron/SchedulingLog')->where(['start_time' => ['ELT', $limit_time]])->delete();
        echo '删除调度运行日志记录数:' . $res;

//        M(;);;

    }
}