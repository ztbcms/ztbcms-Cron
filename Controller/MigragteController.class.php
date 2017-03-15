<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Controller;

use Common\Controller\AdminBase;

/**
 * 迁移脚本
 */
class MigragteController extends AdminBase {

    //2.0.1.2 => 2.1.0.0
    function upgrade2_0_1_2_to_2_1_0_0() {
        $db = D('Cron/Cron');
        $crons = $db->select();

        foreach ($crons as $index => $cron) {
            $pos = strpos($cron['cron_file'], '/');
            if ($pos === false) {
                echo $cron['cron_id'] . ' ' . $cron['cron_file'] . ' ' . $cron['cron_file'] . '<br>';
                $db->where(['cron_id' => $cron['cron_id']])->save(['cron_file' => 'Cron\\CronScript\\' . $cron['cron_file']]);
            }
        }
    }

}