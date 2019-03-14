<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Model;

use Think\Model;

class CronConfigModel extends Model
{

    protected $tableName = 'cron_config';

    //是否启用计划任务
    const KEY_ENABLE_CRON = 'ENABLE_CRON';

    //是否启用
    const ENABLE_YES = 1;
    const ENABLE_NO = 0;
}