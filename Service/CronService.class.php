<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Service;

use System\Service\BaseService;

class CronService extends BaseService
{
    static private $_config = null;

    static function getConfig($forceSync = false)
    {
        if ($forceSync || empty(self::$_config)) {
            $configs = M('CronConfig')->select();
            foreach ($configs as $config) {
                self::$_config[$config['key']] = $config['value'];
            }

        }

        return createReturn(true, self::$_config);
    }
}