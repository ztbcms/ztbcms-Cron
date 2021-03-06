<?php

return array(
    array(
        //父菜单ID，NULL或者不写系统默认，0为顶级菜单
        "parentid" => 37,
        //地址，[模块/]控制器/方法
        "route" => "Cron/Cron/index",
        //类型，1：权限认证+菜单，0：只作为菜单
        "type" => 0,
        //状态，1是显示，0不显示（需要参数的，建议不显示，例如编辑,删除等操作）
        "status" => 1,
        //名称
        "name" => "计划任务",
        //备注
        "remark" => "计划任务是一项使系统在规定时间自动执行某些特定任务的功能！",
        //子菜单列表
        "child" => array(
            array(
                "route" => "Cron/Cron/dashboard",
                "type" => 1,
                "status" => 1,
                "name" => "概览",
            ),
            array(
                "route" => "Cron/Cron/index",
                "type" => 1,
                "status" => 1,
                "name" => "计划任务列表",
            ),
            array(
                "route" => "Cron/Cron/add",
                "type" => 1,
                "status" => 1,
                "name" => "添加计划任务",
            ),
            array(
                "route" => "Cron/Cron/edit",
                "type" => 1,
                "status" => 0,
                "name" => "编辑计划任务",
            ),
            array(
                "route" => "Cron/Cron/delete",
                "type" => 1,
                "status" => 0,
                "name" => "删除计划任务",
            ),
            array(
                "route" => "Cron/Cron/logs",
                "type" => 1,
                "status" => 0,
                "name" => "计划任务日志列表页",
            ),
            array(
                "route" => "Cron/Cron/getCronLogs",
                "type" => 1,
                "status" => 0,
                "name" => "获取计划任务列表操作",
            ),
            array(
                "route" => "Cron/Cron/scheduling_logs",
                "type" => 1,
                "status" => 0,
                "name" => "调度执行日志列表页",
            ),
            array(
                "route" => "Cron/Cron/getSchedulingLogs",
                "type" => 1,
                "status" => 0,
                "name" => "获取调度执行日志列表操作",
            ),
            array(
                "route" => "Cron/Cron/setCronEnable",
                "type" => 1,
                "status" => 0,
                "name" => "设置计划任务开关",
            ),
            array(
                "route" => "Cron/Cron/setCronSecretKey",
                "type" => 1,
                "status" => 0,
                "name" => "设置密钥",
            ),
        ),
    ),
);
