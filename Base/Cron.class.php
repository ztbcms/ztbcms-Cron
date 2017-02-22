<?php

// +----------------------------------------------------------------------
// | Author: Jayin Ton <tonjayin@gmail.com>
// +----------------------------------------------------------------------

namespace Cron\Base;

/**
 * Class Cron
 *
 * @package Cron\Base
 */
abstract class Cron {

    /**
     * 执行任务回调
     *
     * @param string $cronId
     */
    abstract public function run($cronId);
}