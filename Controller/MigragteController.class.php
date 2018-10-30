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

    protected function _initialize() {
        parent::_initialize();

        if($this->userInfo['role_id'] != 1){
            //没有操作权限
            $this->error('非超级管理员，无法操作！');
        }

        set_time_limit(0);
        ignore_user_abort(true);
    }

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

    //v2.1.2.1 => 2.2.0.0
    function upgrade2_1_2_1_to_2_2_0_0(){
        $this->log('迁移开始.....');
        $db = D('Cron/Cron');
        $tableName = C("DB_PREFIX") . 'cron_log';

        $CronLogDb = M('CronLog');
        $fields = $CronLogDb->getDbFields();

        //新增cron_log 表新增字段 use_time
        $this->log('新增cron_log 表新增字段 use_time');
        if(!in_array('use_time',$fields)){
            $sql = "ALTER TABLE `{$tableName}` ADD `use_time` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '耗时'";
            $db->execute($sql);
            $this->fixCronLogUsetimeField();
            $this->log('新增cron_log 表新增字段 use_time  完成！');
        }else{
            $this->log('cron_log 表新增字段 use_time  已经存在');
        }

        $this->log('迁移完成！');
    }

    private function fixCronLogUsetimeField($start_id = 0){
        $db = M('CronLog');
        $result = $db->where(['id' => ['GT', $start_id]])->find();
        while($result){
            $this->log('Handle Cron.id => ' . $result['id']);
            $use_time = $result['end_time'] - $result['start_time'];

            $tableName = C("DB_PREFIX") . 'cron_log';
            $update_sql = "update `{$tableName}` set use_time = '{$use_time}' where id= '{$result['id']}'";
            $db->execute($update_sql);

            $result = $db->where(['id' => ['GT', $result['id']]])->find();
        }
    }

    private function log($msg){
        echo $msg;
        echo '<br>';
    }

}