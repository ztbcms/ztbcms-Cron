<?php

/**
 * author: Jayin <tonjayin@gmail.com>
 */

namespace Cron\Controller;

use Common\Controller\Base;
use Cron\Model\CronConfigModel;
use Cron\Service\CronService;

/**
 * 私钥校验
 */
class AuthCronController extends Base {

    protected function _initialize() {
        parent::_initialize();

        $cron_config = CronService::getConfig()['data'];
        $cron_secret_key = I('get.cron_secret_key', '');

        if ($cron_secret_key != $cron_config[CronConfigModel::KEY_ENABLE_SECRET_KEY]) {
            $this->ajaxReturn(self::createReturn(false, null, 'Secret key invalidated'));
        }
    }


}