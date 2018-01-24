<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Model;

use Common\Model\RelationModel;

/**
 * 计划任务日志
 */
class OperationlogModel extends RelationModel {

    protected $tableName = 'operationlog';

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
}