<?php

// +----------------------------------------------------------------------
// | 配置
// +----------------------------------------------------------------------

return array(
    'CRON_MAX_TIME' => 30000, //计划任务最大执行秒数，暂时无用
    'CRON_SECRET_KEY' => '', //计划任务私钥
    'CRON_LOG' => true, //是否开启计划任务日志，默认 ture,开启
    'CRON_SCHEDULING_LOG' => true //是否开启计划任务调度运行日志，默认 ture,开启
);
