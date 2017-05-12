<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Model;

use Common\Model\RelationModel;

/**
 * 计划任务日志
 */
class CronLogModel extends RelationModel {

    protected $tableName = 'cron_log';

    /**
     * 运行结果：成功
     */
    const RESULT_SUCCESS = 1;
    /**
     * 运行结果：失败
     */
    const RESULT_FAIL = 2;

    /**
     * 关联表
     *
     * @var array
     */
    protected $_link = array(
        //关联滤芯
        'cronData' => array(
            "mapping_type" => self::HAS_ONE,
            "class_name" => 'Cron/Cron',
            "foreign_key" => "cron_id",
            "mapping_key" => "cron_id",
        ),

    );

}