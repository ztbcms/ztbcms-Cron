<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\CronScript;

use Cron\Base\Cron;

/**
 * 删除管理后台的日志(默认删除30日前的日志)
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
        \Think\Log::record("Run:Deleteoperationlog");
        $time = time() - 30 * 24 * 60 * 60;
        $where['time'] = array('ELT', $time);
        $res = D('Cron/Operationlog')->where($where)->delete();
        echo '删除日志记录数:' . $res;
    }
}