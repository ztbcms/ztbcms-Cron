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

        return self::createReturn(true, self::$_config);
    }

    static function setConfig($key, $value)
    {
        M('CronConfig')->where([
            'key' => $key
        ])->save(['value' => $value]);

        return self::createReturn(true, null, '操作成功');
    }


}